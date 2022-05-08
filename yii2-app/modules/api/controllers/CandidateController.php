<?php

declare(strict_types=1);

namespace app\modules\api\controllers;

use Yii;
use yii\rest\Controller;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use app\modules\api\readModels\CandidateReadRepository;
use app\modules\api\providers\MapDataProvider;
use app\modules\api\entities\Candidate;
use app\modules\api\entities\CandidateFramework;
use app\modules\api\services\CandidateService;
use app\modules\api\forms\CandidateCreateForm;
use app\modules\api\forms\CandidateUpdateForm;
use app\modules\api\dto\CandidateDto;

final class CandidateController extends Controller
{
    private CandidateReadRepository $candidates;
    private CandidateService $service;

    public function __construct($id, $module, CandidateReadRepository $candidates, CandidateService $service, $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->candidates = $candidates;
        $this->service = $service;
    }

    public function verbs(): array
    {
        return [
            'index' => ['GET'],
            'view' => ['GET'],
            'store' => ['POST'],
            'update' => ['POST'],
            'destroy' => ['DELETE'],
        ];
    }

    public function actionIndex(): MapDataProvider
    {
        $dataProvider = $this->candidates->getAll();

        return new MapDataProvider($dataProvider, [$this, 'serializeListItem']);
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionView(int $id): array
    {
        if (! $candidate = $this->candidates->find($id)) {
            throw new NotFoundHttpException('The requested candidate does not exist.');
        }

        return $this->serializeItem($candidate);
    }

    public function actionStore(): array
    {
        $form = new CandidateCreateForm();
        $form->resumeFile = UploadedFile::getInstanceByName('resume');
        $form->setAttributes(Yii::$app->request->post());

        if ($form->validate()) {
            $form->upload();

            $this->service->create(CandidateDto::fromArray($form->getAttributes()));

            Yii::$app->response->setStatusCode(201);
            return [];
        }

        Yii::$app->response->setStatusCode(422);
        return $form->getErrors();
    }

    public function actionUpdate(int $id): array
    {
        $form = new CandidateUpdateForm();
        $form->resumeFile = UploadedFile::getInstanceByName('resume');
        $form->setAttributes(Yii::$app->request->post());

        if ($form->validate()) {
            $form->upload();

            $this->service->edit($id, CandidateDto::fromArray($form->getAttributes()));
            return [];
        }

        Yii::$app->response->setStatusCode(422);
        return $form->getErrors();
    }

    public function actionDestroy(int $id): array
    {
        $this->service->remove($id);
        return [];
    }

    public function serializeListItem(Candidate $candidate): array
    {
        return [
            'id' => $candidate->id,
            'name' => $candidate->name,
            'birthday' => $candidate->birthday,
            'experience' => $candidate->experience,
            'resume' => Url::to('@web' . $candidate->resume, true),
            'comment' => $candidate->comment,
            'created_at' => $candidate->created_at,
            'frameworks' => ArrayHelper::toArray($candidate->candidateFrameworks, [
                CandidateFramework::class => [
                    'id' => 'framework.id',
                    'name' => 'framework.name',
                ],
            ])
        ];
    }

    private function serializeItem(Candidate $candidate): array
    {
        return $this->serializeListItem($candidate);
    }
}
