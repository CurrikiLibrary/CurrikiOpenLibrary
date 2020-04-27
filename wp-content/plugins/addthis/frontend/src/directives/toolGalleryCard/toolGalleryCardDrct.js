appAddThisWordPress.directive('toolGalleryCard', function($wordpress) {
  return {
    scope: {
      image: '@image',
      imageAltMsgid: '@imageAltMsgid',
      toolNameMsgid: '@toolNameMsgid',
      toolDescriptionMsgid: '@toolDescriptionMsgid',
      toolEnabled: '=toolEnabled',
      toolPco: '@toolPco',
      changeState: '=changeState',
      toggleEvent: '=toggleEvent'
    },
    transclude: true,
    link: function($scope, el, attrs) {
      $scope.templateBaseUrl = $wordpress.templateBaseUrl();

      $scope.isProTool = function() {
        return (typeof attrs.pro !== 'undefined');
      };
    },
    templateUrl: '/directives/toolGalleryCard/toolGalleryCard.html'
  };
});