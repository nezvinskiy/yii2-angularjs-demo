// AngularJS
const angular = require('angular');

require('angular-route');
const moment = require('moment');

angular
    .module('candidateApp', [
        'ngRoute',
    ])

    .constant(
        'API_URL', 'http://localhost:8080/api',
    )

    .service('CandidateService', function($http, API_URL) {
        this.get = function(sort = '') {
            return $http.get(API_URL + '/candidates' + (sort ? '?sort=' + sort : ''));
        };
        this.getById = function(id) {
            return $http.get(API_URL + '/candidates/' + id);
        };
        this.store = function (data) {
            return $http.post(API_URL + '/candidates', data, {
                transformRequest: angular.identity,
                headers: { 'Content-Type': undefined }
            });
        };
        this.update = function (id, data) {
            return $http.post(API_URL + '/candidates/' + id, data, {
                transformRequest: angular.identity,
                headers: { 'Content-Type': undefined }
            });
        };
        this.delete = function (id) {
            return $http.delete(API_URL + '/candidates/' + id);
        };
    })

    .service('FrameworkService', function($http, API_URL) {
        this.get = function() {
            return $http.get(API_URL + '/frameworks');
        };
    })

    .config(['$routeProvider',
        function config($routeProvider) {
            $routeProvider.
            when('/candidates', {
                template: '<candidate-list></candidate-list>'
            }).
            when('/candidates/add', {
                template: '<candidate-add></candidate-add>'
            }).
            when('/candidates/:candidateId', {
                template: '<candidate-edit></candidate-edit>'
            }).
            otherwise('/candidates');
        }
    ])

    .directive('fileModel', ['$parse', function ($parse) {
        return {
            restrict: 'A',
            link: function(scope, element, attrs) {
                const model = $parse(attrs.fileModel);
                const modelSetter = model.assign;

                element.bind('change', function() {
                    scope.$apply(function() {
                        modelSetter(scope, element[0].files[0]);
                    });
                });
            }
        };
    }])

    .component('candidateList', {
        templateUrl: 'pages/candidates/list.html',
        controller: [
            '$scope',
            'CandidateService',
            function CandidateListController($scope, CandidateService) {
                $scope.sortDirection = '-';
                $scope.sort = 'created_at';

                $scope.getList = function() {
                    $scope.sortDirection = $scope.sortDirection === '-' ? '' : '-';
                    let sort = $scope.sortDirection + $scope.sort;

                    CandidateService.get(sort).then(function (res) {
                        $scope.candidates = res.data
                    }, function (err) {
                        // console.log(err);
                    });
                };

                $scope.getList();

                $scope.remove = function(id) {
                    if (confirm('Are you sure you want to delete?')) {
                        CandidateService.delete(id).then(function () {
                            $scope.getList();
                        }, function (err) {
                            // console.log(err);
                        });
                    }
                };
            }
        ]
    })

    .component('candidateAdd', {
        templateUrl: 'pages/candidates/add.html',
        controller: [
            '$scope',
            '$location',
            'CandidateService',
            'FrameworkService',
            function CandidateAddController($scope, $location, CandidateService, FrameworkService) {
                $scope.errorMessages = [];

                $scope.nameModel = '';
                $scope.birthdayModel = '';
                $scope.experienceModel = '';
                $scope.resumeFile = '';
                $scope.commentModel = '';
                $scope.frameworkModel = {};

                $scope.getFrameworks = function() {
                    FrameworkService.get().then(function (res) {
                        $scope.frameworks = res.data
                    }, function (err) {
                        // console.log(err);
                    });
                };
                $scope.getFrameworks();

                $scope.store = function() {
                    $scope.errorMessages = [];

                    const birthday = moment($scope.birthdayModel).format('YYYY-MM-DD');

                    const formData = new FormData();
                    formData.append('name', $scope.nameModel);
                    formData.append('birthday', birthday);
                    formData.append('experience', $scope.experienceModel);
                    formData.append('comment', $scope.commentModel);
                    formData.append('resume', $scope.resumeFile);

                    for (const [key, value] of Object.entries($scope.frameworkModel)) {
                        if (value) {
                            formData.append('frameworks[]', key)
                        }
                    }

                    CandidateService.store(formData).then(function (res) {
                        if (res.status === 200 || res.status === 201) {
                            $location.path('/');
                        }
                    }, function (err) {
                        if (err.status === 422) {
                            for (const [key, value] of Object.entries(err.data)) {
                                if (value.length) {
                                    $scope.errorMessages.push(value[0]);
                                }
                            }
                        }
                    });
                };
            }
        ]
    })

    .component('candidateEdit', {
        templateUrl: 'pages/candidates/edit.html',
        controller: [
            '$scope',
            '$routeParams',
            '$location',
            'CandidateService',
            'FrameworkService',
            function CandidateEditController($scope, $routeParams, $location, CandidateService, FrameworkService) {
                $scope.candidateId = $routeParams.candidateId;
                $scope.candidate = {};

                $scope.errorMessages = [];
                $scope.resumeFile = '';
                $scope.frameworkModel = {};

                $scope.getCandidateById = function(id) {
                    CandidateService.getById(id).then(function (res) {
                        $scope.candidate = res.data;
                        $scope.nameModel = $scope.candidate.name;
                        $scope.birthdayModel = moment($scope.candidate.birthday).toDate();
                        $scope.experienceModel = $scope.candidate.experience;
                        $scope.commentModel = $scope.candidate.comment;
                        $scope.candidate.frameworks.forEach(item => $scope.frameworkModel[item.id] = true);
                    }, function (err) {
                        // console.log(err);
                    });
                };
                $scope.getCandidateById($scope.candidateId);

                $scope.getFrameworks = function() {
                    FrameworkService.get().then(function (res) {
                        $scope.frameworks = res.data
                    }, function (err) {
                        // console.log(err);
                    });
                };
                $scope.getFrameworks();

                $scope.update = function() {
                    $scope.errorMessages = [];

                    const birthday = moment($scope.birthdayModel).format('YYYY-MM-DD');

                    const formData = new FormData();
                    formData.append('name', $scope.nameModel);
                    formData.append('birthday', birthday);
                    formData.append('experience', $scope.experienceModel);
                    formData.append('comment', $scope.commentModel);
                    formData.append('resume', $scope.resumeFile);

                    for (const [key, value] of Object.entries($scope.frameworkModel)) {
                        if (value) {
                            formData.append('frameworks[]', key)
                        }
                    }

                    CandidateService.update($scope.candidateId, formData).then(function (res) {
                        if (res.status === 200 || res.status === 201) {
                            $location.path('/');
                        }
                    }, function (err) {
                        if (err.status === 422) {
                            for (const [key, value] of Object.entries(err.data)) {
                                if (value.length) {
                                    $scope.errorMessages.push(value[0]);
                                }
                            }
                        }
                    });
                };
            }
        ]
    })
;
