appAddThisWordPress.directive('toolGallerySettingsPage', function(
  $wordpress,
  $q,
  $stateParams,
  $darkseid
) {
  return {
    scope: {
      parentSave: '=save', // bi-directional
      parentGoBack: '=goBack', // bi-directional
      toolNameMsgid: '@toolNameMsgid',
      toolDescriptionMsgid: '@toolDescriptionMsgid',
      featureSettings: '=featureSettings',
      toolPco: '@toolPco'
    },
    transclude: true,
    link: function($scope, el, attrs, ctrl, transclude) {
      $scope.saving = false;
      $scope.isProUser = false;
      $scope.proFields = {};

      var isProTool = function() {
        return (typeof attrs.pro !== 'undefined');
      };

      $scope.upgradePrompt = function() {
        if ($scope.isProUser === true) {
          return false;
        } else if (isProTool()) {
          return true;
        } else if (usingProField()) {
          return true;
        } else {
          return false;
        }
      };

      var usingProField = function() {
        var usingProField = false;
        angular.forEach($scope.proFields, function(inUse) {
          if (inUse === true) {
            usingProField = true;
          }
        });

        return usingProField;
      };

      var proProfilePromise = $darkseid.isProProfile();
      var globalOptionsPromise = $wordpress.globalOptions.get();

      $q.all([proProfilePromise, globalOptionsPromise]).then(function(data) {
        var proProfile = data[0];
        var globalOptions = data[1];

        if (proProfile &&
          angular.isDefined(globalOptions.addthis_plugin_controls) &&
          globalOptions.addthis_plugin_controls === 'AddThis'
        ) {
          $scope.isProUser = true;
        }
      });

      var stashOldSettings = function(newSettings) {
        $scope.originalSettings = angular.copy(newSettings[$scope.toolPco]);
        $scope.settings = $wordpress.addDefaultToolConfigurations(
          $scope.toolPco,
          newSettings[$scope.toolPco]
        );
      };

      $scope.$watch('featureSettings', function(newValue) {
        stashOldSettings(newValue);
      });

      if (!angular.isDefined($scope.featureSettings[$scope.toolPco])) {
        $scope.featureSettings[$scope.toolPco] = {};
      }

      stashOldSettings($scope.featureSettings);

      var transcludeElements = el
      .find('.transclude-here-tool-gallery-settings-page');

      if (transcludeElements.length === 0) {
        // for older version of jQuery :-(
        transcludeElements = jQuery(el)
        .find('[class*="transclude-here-tool-gallery-settings-page"]');
      }
      transcludeElements.append(transclude($scope));

      $scope.showToolSettings = function() {
        if (angular.isDefined($stateParams.toolPco) &&
          angular.isDefined($scope.toolPco) &&
          $scope.toolPco === $stateParams.toolPco
        ) {
          return true;
        } else {
          return false;
        }
      };
    },
    controller: function($scope) {
      $scope.save = function() {
        if ($scope.upgradePrompt()) {
          return false;
        }

        $scope.saving = true;

        if (typeof $scope.featureSettings[$scope.toolPco] === 'undefined') {
          $scope.featureSettings[$scope.toolPco] = {};
        }
        $scope.featureSettings[$scope.toolPco].enabled = true;

        $scope.parentSave($scope.toolPco).then(function() {
          $scope.goBack();
          $scope.saving = false;
        });
      };

      $scope.goBack = function() {
        var copy = angular.copy($scope.originalSettings);
        $scope.featureSettings[$scope.toolPco] = copy;
        $scope.parentGoBack();
      };
    },
    templateUrl:
    '/directives/toolGallerySettingsPage/toolGallerySettingsPage.html'
  };
});