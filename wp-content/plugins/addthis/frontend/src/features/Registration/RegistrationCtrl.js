appAddThisWordPress.controller('RegistrationCtrl', function(
  $scope,
  $q,
  $wordpress,
  $filter,
  $stateParams,
  $state,
  $darkseid
) {
  $scope.globalOptions = {};
  $scope.registrationFormModel = {};
  $scope.successfulNewRegistration = false;
  $scope.loadingStatus = true;
  $scope.loadingMessage = 'progress_message_loading';

  $scope.templateBaseUrl = $wordpress.templateBaseUrl();
  jQuery('[id="wpcontent"]').attr('class', 'registration-page');

  var defaultErrorMessage = $filter('translate')('error_message_unknown_error');
  var defaultErrorObject = {
    failed: false,
    title: 'error_message_error_occured',
    message: $filter('translate')('error_message_failed_unknown_reason')
  };
  var originalRegistration = {};

  $scope.registrationState = function() {
    if (angular.isDefined($stateParams.registrationState)) {
      return $stateParams.registrationState;
    } else {
      return 'unknown';
    }
  };

  var profileIsGood = function() {
    var goodSetup = true;
    var deferred = $q.defer();

    if (!$scope.globalOptions.addthis_profile) {
      goodSetup = false;
      deferred.resolve(goodSetup);
    }

    $darkseid.validateAddThisProfileId($scope.globalOptions.addthis_profile)
    .then(function(data) {
      if (!data.success) {
        goodSetup = false;
      }

      deferred.resolve(goodSetup);
    });

    return deferred.promise;
  };

  var apiKeyIsGood = function() {
    var goodSetup = true;
    var deferred = $q.defer();

    if (!$scope.globalOptions.api_key) {
      goodSetup = false;
      deferred.resolve(goodSetup);
    }

    $wordpress.addThisApiKeyCheck(
      $scope.globalOptions.addthis_profile,
      $scope.globalOptions.api_key
    )
    .then(function(data) {
      if (!data.success) {
        goodSetup = false;
      }

      deferred.resolve(goodSetup);
    });

    return deferred.promise;
  };

  var setupCheck = function() {
    var result = profileIsGood().then(function(profileGood) {
      if ($scope.minimalistProPlugin() || profileGood === false) {
        return profileGood;
      } else {
        return apiKeyIsGood();
      }
    });

    return result;
  };

  var bootstrapGlobalOptions = function(globalOptions) {
    $scope.registrationFormModel = {};
    $scope.registrationFormModel.emailSubscription = true;
    if($wordpress.defaults('email')) {
      $scope.registrationFormModel.email = $wordpress.defaults('email');
    }

    if ($wordpress.defaults('profileName')) {
      $scope.registrationFormModel.profileName =
        $wordpress.defaults('profileName');
    } else {
      $scope.registrationFormModel.profileName =
        $filter('translate')('registration_first_profile_name_fallback');
    }

    $scope.globalOptions = globalOptions;
    $scope.loadingStatus = true;
    $scope.loadingMessage = 'progress_message_checking_registration';

    originalRegistration = {};

    if (angular.isDefined(globalOptions.addthis_profile)) {
      originalRegistration.addthis_profile = globalOptions.addthis_profile;
    }

    if (angular.isDefined(globalOptions.api_key)) {
      originalRegistration.api_key = globalOptions.api_key;
    }

    return setupCheck().then(function(setupGood) {
      if (!angular.isDefined($stateParams.registrationState) ||
        ( $stateParams.registrationState !== 'signIn' &&
          $stateParams.registrationState !== 'createAccount' &&
          $stateParams.registrationState !== 'manual')
      ) {
        if(setupGood) {
          $state.go('registration.state', {registrationState: 'registered'});
        } else {
          $state.go('registration.state', {registrationState: 'signIn'});
        }
      }
      $scope.loadingStatus = false;
    });
  };

  $wordpress.globalOptions.get().then(function(globalOptions) {
    bootstrapGlobalOptions(globalOptions);
  });

  $scope.minimalistProPlugin = function() {
    if ($scope.globalOptions.recommended_content_feature_enabled ||
        $scope.globalOptions.follow_buttons_feature_enabled ||
        $scope.globalOptions.sharing_buttons_feature_enabled
    ) {
      return false;
    } else {
      return true;
    }
  };

  $scope.signInFailed = false;
  $scope.signInErrorMessage = defaultErrorMessage;
  $scope.signInSubmit = function(valid) {
    if(!valid) {
      return;
    }

    var email = $scope.registrationFormModel.email;
    var password = $scope.registrationFormModel.password;
    $scope.loadingStatus = true;
    $scope.loadingMessage = 'progress_message_checking_login';

    $wordpress.addThisCheckLogin(email, password).then(function(data) {
      $scope.signInFailed = !data.success;

      if (data.message) {
        $scope.signInErrorMessage = data.message;
      }

      if (data.success === true) {
        $scope.loadingStatus = true;
        $scope.loadingMessage = 'progress_message_retrieving_profiles';
        $wordpress.addThisGetProfiles(email, password).then(function(data) {
          populateProfiles(data.data);
        });
      } else {
        $scope.loadingStatus = false;
      }
    });
  };

  $scope.warnOfProfileNotFoundOnAccount = false;
  $scope.profileIdFoundOnAccount = false;
  var populateProfiles = function(profiles) {
    $scope.profileIdFoundOnAccount = false;
    var createOption = {
      name: $filter('translate')('registration_select_create_new_profile'),
      pubId: ''
    };
    $scope.profiles = [];
    $scope.profiles.push(createOption);

    profiles.forEach(function(element) {
      if (element.admin === true) {
        if (element.pubId === $scope.globalOptions.addthis_profile) {
          $scope.profileIdFoundOnAccount = true;
          $scope.registrationFormModel.profile = element.pubId;
        }

        $scope.profiles.push(element);
      }
    });

    if ($scope.profileIdFoundOnAccount === false) {
      $scope.registrationFormModel.profile = createOption.pubId;
    }

    if ($scope.globalOptions.addthis_profile === '') {
      $scope.warnOfProfileNotFoundOnAccount = false;
    } else {
      $scope.warnOfProfileNotFoundOnAccount = !$scope.profileIdFoundOnAccount;
    }

    $scope.loadingStatus = false;
    $state.go('registration.state', {registrationState: 'selectProfile'});
  };

  $scope.selectProfileSubmit = function() {
    if($scope.registrationFormModel.profile !== '') {
      $scope.globalOptions.addthis_profile =
        $scope.registrationFormModel.profile;
      var email = $scope.registrationFormModel.email;
      var password = $scope.registrationFormModel.password;
      var profileId = $scope.registrationFormModel.profile;
      createApiKeyAndSave(email, password, profileId);
    } else {
      $state.go('registration.state', {registrationState: 'createProfile'});
    }
  };

  $scope.createApiKeyAndSaveStatus = defaultErrorObject;
  var createApiKeyAndSave = function(email, password, profileId) {
    $scope.createApiKeyAndSaveStatus.failed = false;
    $scope.loadingStatus = true;
    $scope.loadingMessage = 'progress_message_creating_api_key';

    var createApiKey = function() {
      var promise = $wordpress.addThisCreateApiKey(email, password, profileId)
      .then(function(data) {
        if (data.success === true) {
          $scope.globalOptions.api_key = data.apiKey;
        } else {
          $scope.createApiKeyAndSaveStatus.failed = true;
          $scope.createApiKeyAndSaveStatus.title =
            'error_message_failed_to_create_api_key';

          if (data.message ===
              'An application of this name already exists for this publisher'
          ) {
            $scope.createApiKeyAndSaveStatus.message =
              $filter('translate')('error_message_too_many_api_key_requests');
          } else {
            $scope.createApiKeyAndSaveStatus.message = data.message;
          }

          $scope.loadingStatus = false;
        }

        return data;
      });
      return promise;
    };

    var changeProfileType = function() {
      var apiKey = $scope.globalOptions.api_key;

      $scope.loadingStatus = true;
      $scope.loadingMessage = 'progress_message_changing_profile_type';

      var promise = $wordpress.addThisChangeProfileType(profileId, apiKey)
      .then(function(data) {
        if (data.success !== true) {
          $scope.createApiKeyAndSaveStatus.failed = true;
          $scope.createApiKeyAndSaveStatus.title =
            'error_message_failed_to_change_profile_type';
          $scope.createApiKeyAndSaveStatus.message = data.message;
        }

        return data;
      });
      return promise;
    };

    var checkProfileType = function() {
      $scope.loadingStatus = true;
      $scope.loadingMessage = 'progress_message_checking_profile_type';

      var profileTypeFixPromise = $darkseid.validateAddThisProfileId(profileId)
      .then(function(data) {
        if (data.success !== true) {
          $scope.createApiKeyAndSaveStatus.failed = true;
          if (data.message) {
            $scope.createAccountErrorMessage = data.message;
          }

        // change how we look up a profile type here
        } else if (data.data.type !== 'wp') {
          var createProfileTypeChangePromise = changeProfileType();
          return createProfileTypeChangePromise;
        }
      });

      return profileTypeFixPromise;
    };

    createApiKey().then(function() {
      checkProfileType().then(function() {
        $scope.loadingStatus = true;
        $scope.loadingMessage = 'progress_message_saving_registration';

        $scope.globalOptions.addthis_plugin_controls = 'AddThis';
        $wordpress.globalOptions.save().then(function(data) {
          $scope.globalOptions = data;
          if(!$scope.createApiKeyAndSaveStatus.failed) {
            $scope.successfulNewRegistration = true;
            $state.go('registration.state', {registrationState: 'registered'});
          }
          $scope.loadingStatus = false;
        });
      });
    });
  };

  $scope.createAccountShow = function() {
    $state.go('registration.state', {registrationState: 'createAccount'});
  };

  $scope.editManually = function() {
    $state.go('registration.state', {registrationState: 'manual'});
  };

  $scope.signIn = function() {
    $state.go('registration.state', {registrationState: 'signIn'});
  };

  $scope.createAccountFailed = false;
  $scope.createAccountErrorMessage = defaultErrorMessage;
  $scope.createAccountSubmit = function(valid) {
    if(!valid) {
      return;
    }

    var email = $scope.registrationFormModel.email;
    var password = $scope.registrationFormModel.password;
    var newsletter = $scope.registrationFormModel.emailSubscription;
    $scope.loadingStatus = true;
    $scope.loadingMessage = 'progress_message_checking_account';

    $wordpress.addThisCreateAccount(email, password, newsletter)
    .then(function(data) {
      $scope.createAccountFailed = !data.success;

      if(data.success === true) {
        $scope.loadingStatus = true;
        $scope.loadingMessage = 'progress_message_retrieving_profile';
        $wordpress.addThisGetProfiles(email, password).then(function(data) {
            if (data.success) {
              $scope.globalOptions.addthis_profile = data.data[0].pubId;
              createApiKeyAndSave(
                email,
                password,
                $scope.globalOptions.addthis_profile
              );
            }
        });
      } else if (data.message) {
        $scope.loadingStatus = false;
        $scope.createAccountErrorMessage = data.message;
      }

    });
  };

  $scope.createProfileFailed = false;
  $scope.createProfileSubmit = function(valid) {
    if(!valid) {
      return;
    }
    $scope.createProfileFailed = false;

    var email = $scope.registrationFormModel.email;
    var password = $scope.registrationFormModel.password;
    var profileName = $scope.registrationFormModel.profileName;

    $scope.loadingStatus = true;
    $scope.loadingMessage = 'progress_message_creating_profile';

    $wordpress.addThisCreateProfile(email, password, profileName)
    .then(function(data) {
      if (data.success === true) {
        $scope.globalOptions.addthis_profile = data.profileId;
        createApiKeyAndSave(email, password, data.profileId);
      } else {
        $scope.createProfileFailed = true;
        $scope.createApiKeyAndSaveStatus.failed = true;
        $scope.createApiKeyAndSaveStatus.title =
          'error_message_failed_to_create_profile';
        $scope.createApiKeyAndSaveStatus.message = data.message;
        $scope.loadingStatus = false;
      }
    });
  };

  $scope.startOver = function() {
    bootstrapGlobalOptions($scope.globalOptions).then(function() {
      $state.go('registration.state', {registrationState: 'signIn'});
    });
  };

  $scope.cancel = function() {
    if (angular.isDefined(originalRegistration.addthis_profile)) {
      $scope.globalOptions.addthis_profile =
        originalRegistration.addthis_profile;
    } else if (angular.isDefined($scope.globalOptions.addthis_profile)) {
      delete $scope.globalOptions.addthis_profile;
    }

    if (angular.isDefined(originalRegistration.api_key)) {
      $scope.globalOptions.api_key = originalRegistration.api_key;
    } else if (angular.isDefined($scope.globalOptions.api_key)) {
      delete $scope.globalOptions.api_key;
    }

    $scope.startOver();
  };
});