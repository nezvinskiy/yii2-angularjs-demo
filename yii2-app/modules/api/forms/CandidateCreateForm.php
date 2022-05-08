<?php

declare(strict_types=1);

namespace app\modules\api\forms;

use yii\base\Model;
use yii\web\UploadedFile;
use app\modules\api\entities\Candidate;

class CandidateCreateForm extends Model
{
    public string $name = '';
    public string $birthday = '';
    public int $experience = 0;
    public string $resume = '';
    public ?UploadedFile $resumeFile;
    public string $comment = '';
    public array $frameworks = [];
    public string $createdAt = '';

    private ?Candidate $candidate;

    public function __construct(Candidate $candidate = null, $config = [])
    {
        $this->candidate = $candidate;
        parent::__construct($config);
    }

    public function init(): void
    {
        if ($this->candidate) {
            $this->name = $this->candidate->name;
            $this->birthday = $this->candidate->birthday;
            $this->experience = $this->candidate->experience;
            $this->comment = $this->candidate->comment;
        }
        $this->createdAt = date('Y-m-d H:i:s');
    }

    public function upload(): void
    {
        if ($this->resumeFile) {
            $this->resume = '/uploads/' . $this->makeFileName() . '.' . $this->resumeFile->extension;
            $this->resumeFile->saveAs('@webroot' . $this->resume);
        }
    }

    private function makeFileName(): string
    {
        return str_replace('.', '-', microtime(true));
    }

    public function rules(): array
    {
        return [
            [['name', 'birthday', 'experience', 'resumeFile',], 'required'],
            [['name', 'comment',], 'string', 'max' => 255],
            [['experience',], 'integer',],
            [['birthday',], 'date', 'format' => 'php:Y-m-d'],
            [['resumeFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'pdf'],
            [['frameworks',], 'each', 'rule' => ['integer']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'name' => 'Name',
            'birthday' => 'Birthday',
            'experience' => 'Experience',
            'resumeFile' => 'CV',
            'comment' => 'Comment',
            'frameworks' => 'PHP Frameworks',
        ];
    }
}
