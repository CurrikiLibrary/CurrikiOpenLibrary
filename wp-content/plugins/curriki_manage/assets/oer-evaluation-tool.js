jQuery(function () {
  var $ = jQuery;
  var oer = {};
  oer.materials = {};
  IS_AUTHENTICATED = $("body").hasClass("authenticated");
  HONEYPOT_FIELD_NAME = "address";
  $.expr[":"].econtains = function (obj, index, meta) {
    return $.trim((obj.textContent || obj.innerText || $(obj).text() || "")).toLowerCase() == $.trim(meta[3]).toLowerCase();
  };
  DEFAULT_TOOLTIP_OPTIONS = {content: {text: function () {
        return $($(this).attr("rel"));
      }, title: function () {
        return $(this).text();
      }}, position: {my: "right center", at: "left center", target: "event"}, style: {classes: "ui-tooltip-dark-blue ui-tooltip-shadow fl-inverted-color"}, show: {event: 'focus mouseenter'}, hide: {event: 'blur mouseout'}};
  RIGHTSIDE_TOOLTIP_OPTIONS = $.extend(true, {}, DEFAULT_TOOLTIP_OPTIONS, {position: {my: "left center", at: "right center"}});
  TOPLEFTSIDE_TOOLTIP_OPTIONS = $.extend(true, {}, DEFAULT_TOOLTIP_OPTIONS, {position: {my: "right bottom", at: "left top"}});
  function rcorners($els) {
    if (window.rocon) {
      $els.each(function (i, el) {
        window.rocon.update(el);
      });
    }
  }
  (function () {
    var BaseEvaluationTool, EvaluationResults, EvaluationTool, TriStateEvaluationTool, __hasProp = {}.hasOwnProperty, __extends = function (child, parent) {
      for (var key in parent) {
        if (__hasProp.call(parent, key))
          child[key] = parent[key];
      }
      function ctor() {
        this.constructor = child;
      }
      ctor.prototype = parent.prototype;
      child.prototype = new ctor();
      child.__super__ = parent.prototype;
      return child;
    };
    BaseEvaluationTool = (function () {
      function BaseEvaluationTool() {
        var hash, result, rubric, tag;
        this.ct = $("div.rubrics");
        this.alignmentRubric = $("#alignment");
        this.evaluateURL = this.ct.data("evaluate-url");
        this.rubrics = this.ct.children("section");
        this.tagsCt = this.ct.find("div.tags");
        this.scoreSelectors = this.ct.find("div.scores");
        this.rubrics.find("h1 a:first").click((function (_this) {
          return function (e) {
            var rubric;
            e.preventDefault();
            rubric = $(e.target).closest("section");
            if (!rubric.hasClass("expanded")) {
              return _this.openRubric(rubric);
            }
          };
        })(this));
        this.tagsCt.delegate("a.tag", "click", (function (_this) {
          return function (e) {
            e.preventDefault();
            return _this.selectTag($(e.currentTarget));
          };
        })(this));
        this.scoreSelectors.delegate(":radio", "change", (function (_this) {
          return function (e) {
            var score, scoreText, scores, standardClass, tagId, tagSelector, target;
            target = $(e.currentTarget);
            score = target.closest("div");
            scores = target.closest("div.scores");
            if (target.is(":checked")) {
              score.siblings(".selected").removeClass("selected");
              score.addClass("selected");
            }
            rcorners(scores.find("label"));
            tagId = scores.data("tag-id");
            standardClass = scores.data("standard-class");
            if (tagId && standardClass) {
              tagSelector = _this.tagsCt.find("a.tag[data-tag-id='" + tagId + "'][data-standard-class='" + standardClass + "']");
              if (score.is(":last-child")) {
                scoreText = "N/A";
              } else {
                scoreText = score.find("label").text();
              }
              tagSelector.find("span.value").text(scoreText);
              return tagSelector.addClass("scored");
            }
          };
        })(this));
        this.scoreSelectors.find("label").mouseenter((function (_this) {
          return function (e) {
            var target;
            target = $(e.target);
            target.closest("div").addClass("hover");
            return rcorners(target.closest("div.scores").find("a"));
          };
        })(this));
        this.scoreSelectors.find("label").mouseleave((function (_this) {
          return function (e) {
            var target;
            target = $(e.target);
            target.closest("div").removeClass("hover");
            return rcorners(target.closest("div.scores").find("a"));
          };
        })(this));
        this.ct.delegate("a.next-tag,a.prev-tag", "click", (function (_this) {
          return function (e) {
            var standardClass, tag, tagId, target;
            e.preventDefault();
            target = $(e.currentTarget);
            tagId = target.closest("div.footer").data("tag-id");
            standardClass = target.closest("div.footer").data("standard-class");
            tag = _this.tagsCt.find("a.tag[data-tag-id='" + tagId + "'][data-standard-class='" + standardClass + "']");
            if (target.hasClass("next-tag")) {
              tag = tag.next("a.tag");
            } else {
              tag = tag.prev("a.tag");
            }
            return _this.selectTag(tag);
          };
        })(this));
        this.ct.find("a.save,a.add-standard").click((function (_this) {
          return function (e) {
            var section, target;
            e.preventDefault();
            target = $(e.target);
            section = target.closest("section");
            return _this.saveScore(section, function () {
              return window.location.href = target.attr("href");
            });
          };
        })(this));
        this.alignmentRubric.find("a.skip").click((function (_this) {
          return function (e) {
            e.preventDefault();
            _this.alignmentRubric.removeClass("not-scored").addClass("scored");
            return _this.openRubric(_this.alignmentRubric.next("section"));
          };
        })(this));
        hash = window.location.hash;
        result = hash.match(/^#(standard|rubric)(\d+)(\w+)?$/);
        if (result) {
          if (result[1] === "standard") {
            this.openRubric(this.alignmentRubric);
            if (result[2]) {
              tag = this.tagsCt.find("a.tag[data-tag-id='" + result[2] + "'][data-standard-class='" + result[3] + "']");
            } else if (this.tagsCt.find("a.tag").length) {
              tag = this.tagsCt.find("a.tag").first();
            } else {
              tag = null;
            }
            if (tag) {
              this.selectTag(tag);
            }
          } else if (result[1] === "rubric") {
            rubric = this.rubrics.filter("[data-rubric-id='" + result[2] + "']");
            this.openRubric(rubric);
          }
        }
      }
      BaseEvaluationTool.prototype.getScoresFromRubric = function (rubric) {
        return[];
      };
      BaseEvaluationTool.prototype.saveScore = function (rubric, callback) {
        var data, scored, spinner, _i, _len, _ref;
        spinner = rubric.find(".footer .spinner");
        rubric.ajaxStart((function (_this) {
          return function () {
            rubric.unbind("ajaxStart");
            return spinner.addClass("active");
          };
        })(this));
        rubric.ajaxStop((function (_this) {
          return function () {
            rubric.unbind("ajaxStop");
            spinner.removeClass("active");
            if (callback) {
              return callback();
            }
          };
        })(this));
        scored = rubric.find("div.scores div.selected").length;
        _ref = this.getScoresFromRubric(rubric);
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
          data = _ref[_i];
          //$.post(this.evaluateURL, data);
          jQuery('.saving_alert').show();
          jQuery.ajax({
            method: "POST",
            url: this.evaluateURL,
            data: data
          }).done(function( msg ) {
            jQuery('.saving_alert').hide();
          }).always(function(){
            jQuery('.saving_alert').hide();
          });
        }
        if (scored) {
          return rubric.removeClass("not-scored").addClass("scored");
        } else {
          rubric.unbind("ajaxStart");
          rubric.unbind("ajaxStop");
          if (callback) {
            callback();
          }
          return rubric.removeClass("scored").addClass("not-scored");
        }
      };
      BaseEvaluationTool.prototype.openRubric = function (rubric) {
        var currentRubric;
        currentRubric = this.rubrics.filter(".expanded");
        if (!(currentRubric.hasClass("intro") || currentRubric.hasClass("scored"))) {
          currentRubric.addClass("not-scored");
        }
        currentRubric.removeClass("expanded").children(".body").show().slideUp("fast");
        if (!rubric.hasClass("expanded")) {
          return rubric.addClass("expanded").children(".body").hide().slideDown("fast");
        }
      };
      BaseEvaluationTool.prototype.selectTag = function (tag) {
        var standardClass, tagId;
        if (tag.hasClass("selected")) {
          return;
        }
        this.tagsCt.children(".selected").removeClass("selected");
        tagId = tag.data("tag-id");
        standardClass = tag.data("standard-class");
        this.tagsCt.find("div.tag-description[data-tag-id='" + tagId + "'][data-standard-class='" + standardClass + "']").addClass("selected");
        this.tagsCt.find("div.scores[data-tag-id='" + tagId + "'][data-standard-class='" + standardClass + "']").addClass("selected");
        this.tagsCt.find("div.footer[data-tag-id='" + tagId + "'][data-standard-class='" + standardClass + "']").addClass("selected");
        tag.addClass("selected");
        return rcorners(this.tagsCt.find("a.tag"));
      };
      return BaseEvaluationTool;
    })();
    EvaluationTool = (function (_super) {
      __extends(EvaluationTool, _super);
      function EvaluationTool() {
        this.commentBtn = $("a.comment");
        this.commentForm = $("#comment-form");

        this.commentPopup = $("<div/>").qtip(
                {
                  content: this.commentForm,
                  style: {classes: "comment-popup ui-tooltip-rounded ui-tooltip-shadow"},
                  position: {my: "top left", at: "bottom center", target: "event", effect: false, viewport: $(window),container: jQuery('#oerdialog')},
                  show: {target: this.commentBtn, event: "click"},
                  hide: {target: this.commentBtn, event: "click"},
                  events: {
                    show: (function (_this) {
                      return function (e) {
                        var target;
                        target = $(e.originalEvent.target);
                        _this.commentForm.find("textarea").val(target.data("comment"));
                        return _this.commentForm.data("target", target);
                      };
                    })(this),
                    render: function (event, api) {
                      $(this).prependTo(jQuery('#oerdialog'));
                      //jQuery('#oerdialog').qtip('reposition');
                      $(this).qtip('reposition');
                    }
                  } // Events Ends
                }); //qtip Appender Ends

        this.commentForm.find("a.clear-comment").click((function (_this) {
          return function (e) {
            e.preventDefault();
            _this.commentForm.find("textarea").val("");
            _this.commentForm.data("target").removeClass("checked").data("comment", "");
            return _this.hideCommentPopup();
          };
        })(this));
        this.commentForm.find("a.save").click((function (_this) {
          return function (e) {
            var target, text;
            e.preventDefault();
            text = $.trim(_this.commentForm.find("textarea").val());
            target = _this.commentForm.data("target");
            target.data("comment", text);
            if (text === "") {
              target.removeClass("checked");
            } else {
              target.addClass("checked");
            }
            return _this.hideCommentPopup();
          };
        })(this));
        EvaluationTool.__super__.constructor.call(this);
        this.ct.delegate("a.next", "click", (function (_this) {
          return function (e) {
            var rubric;
            e.preventDefault();
            rubric = $(e.currentTarget).closest("section");
            if (!rubric.hasClass("intro")) {
              _this.saveScore(rubric);
            }
            return _this.openRubric(rubric.next("section"));
          };
        })(this));
        this.ct.delegate("a.clear-score", "click", (function (_this) {
          return function (e) {
            var scores, section, standardClass, tagId, target;
            e.preventDefault();
            target = $(e.currentTarget);
            section = target.closest("section");
            if (section.hasClass("rubric")) {
              scores = section.find("div.scores");
            } else {
              tagId = target.closest("div.footer").data("tag-id");
              standardClass = target.closest("div.footer").data("standard-class");
              scores = section.find("div.scores[data-tag-id='" + tagId + "'][data-standard-class='" + standardClass + "']");
            }
            return _this.clearScore(scores);
          };
        })(this));
      }
      EvaluationTool.prototype.hideCommentPopup = function () {
        return this.commentPopup.qtip("api").hide();
      };
      EvaluationTool.prototype.openRubric = function (rubric) {
        this.hideCommentPopup();
        return EvaluationTool.__super__.openRubric.call(this, rubric);
      };
      EvaluationTool.prototype.selectTag = function (tag) {
        this.hideCommentPopup();
        return EvaluationTool.__super__.selectTag.call(this, tag);
      };
      EvaluationTool.prototype.getScoresFromRubric = function (rubric) {
        var data, rubricId, score, scoreId, scores, standardClass, tagId, _i, _len, _ref;
        data = [];
        _ref = rubric.find("div.scores");
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
          scores = _ref[_i];
          score = $(scores).find(".selected");
          if (score.length) {
            scoreId = score.data("score-id");
            tagId = score.parent(".scores").data("tag-id");
            standardClass = score.parent(".scores").data("standard-class");
            rubricId = rubric.data("rubric-id");
            if (tagId && standardClass) {
              data.push({score_id: scoreId, tag_id: tagId, standard_class: standardClass, comment: rubric.find("div.footer[data-tag-id='" + tagId + "'][data-standard-class='" + standardClass + "'] a.comment").data("comment")});
            } else if (rubricId) {
              data.push({score_id: scoreId, rubric_id: rubricId, comment: rubric.find("div.footer a.comment").data("comment")});
            }
          }
        }
        return data;
      };
      EvaluationTool.prototype.clearScore = function (scores) {
        var data, rubric, rubricId, standardClass, tag, tagId;
        rubric = scores.closest("section");
        scores.find(":radio:checked").removeAttr("checked");
        scores.children(".selected").removeClass("selected");
        data = {"delete": "yes"};
        tagId = scores.data("tag-id");
        standardClass = scores.data("standard-class");
        rubricId = rubric.data("rubric-id");
        if (tagId && standardClass) {
          data.tag_id = tagId;
          data.standard_class = standardClass;
          tag = rubric.find("a.tag[data-tag-id='" + tagId + "'][data-standard-class='" + standardClass + "']");
          tag.find("span.value").text("No score");
          tag.removeClass("scored");
        } else if (rubricId) {
          data.rubric_id = rubricId;
        }
        rcorners(scores.find("label"));
        //$.post(this.evaluateURL, data);
        jQuery('.saving_alert').show();
        jQuery.ajax({
          method: "POST",
          url: this.evaluateURL,
          data: data
        }).done(function( msg ) {
          jQuery('.saving_alert').hide();
        }).always(function(){
          jQuery('.saving_alert').hide();
        });
        if (!rubric.find("div.scores :radio:checked").length) {
          return rubric.removeClass("scored").addClass("not-scored");
        }
      };
      return EvaluationTool;
    })(BaseEvaluationTool);
    window.EvaluationTool = EvaluationTool;
    TriStateEvaluationTool = (function (_super) {
      __extends(TriStateEvaluationTool, _super);
      function TriStateEvaluationTool() {
        var rubric, _i, _len, _ref;
        TriStateEvaluationTool.__super__.constructor.call(this);
        this.alignmentRigorRubric = this.rubrics.filter("section[data-alignment-rigor='true']");
        this.overallRatingRubric = this.rubrics.filter("section[data-overall-rating='true']");
        this.ct.delegate("div.category h2 a", "click", (function (_this) {
          return function (e) {
            e.preventDefault();
            return _this.openCategory($(e.currentTarget).closest("div.category"));
          };
        })(this));
        this.rubrics.not($("#alignment")).find("div.scores label").qtip({content: {text: function () {
              return $(this).data("description");
            }}, style: {classes: "comment-popup ui-tooltip-rounded ui-tooltip-shadow", width: 130}, position: {my: "top left", at: "bottom center", effect: false}});
        this.ct.delegate("a.clear-comment", "click", (function (_this) {
          return function (e) {
            var target;
            e.preventDefault();
            target = $(e.currentTarget);
            return target.prev().val("");
          };
        })(this));
        this.ct.delegate("a.next", "click", (function (_this) {
          return function (e) {
            var rubric;
            e.preventDefault();
            rubric = $(e.currentTarget).closest("section");
            if (!rubric.hasClass("intro")) {
              _this.saveScore(rubric);
            }
            if (!rubric.is(_this.overallRatingRubric)) {
              _this.updateOverallRating();
            }
            return _this.openRubric(rubric.next("section"));
          };
        })(this));
        this.ct.delegate("section a.clear-score", "click", (function (_this) {
          return function (e) {
            e.preventDefault();
            return _this.clearScore($(e.currentTarget).closest("section"));
          };
        })(this));
        this.alignmentRubric.delegate("a.clear-score", "click", (function (_this) {
          return function (e) {
            e.preventDefault();
            return _this.clearTagScore(_this.alignmentRubric.find("div.scores.selected"));
          };
        })(this));
        _ref = this.rubrics.not(".intro");
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
          rubric = _ref[_i];
          this.displayScoresInHeader($(rubric));
        }
        this.updateOverallRating();
        this.openRubric(this.alignmentRigorRubric);
        this.openRubric(this.alignmentRigorRubric);
      }
      TriStateEvaluationTool.prototype.getScoreValue = function (rubric) {
        var score;
        score = rubric.find(":radio:checked");
        if (score.length) {
          return parseInt(score.val());
        }
        return null;
      };
      TriStateEvaluationTool.prototype.clearScore = function (rubric) {
        var data;
        data = {"delete": "yes", rubric_id: rubric.data("rubric-id")};
        //$.post(this.evaluateURL, data);
        jQuery('.saving_alert').show();
        jQuery.ajax({
          method: "POST",
          url: this.evaluateURL,
          data: data
        }).done(function( msg ) {
          jQuery('.saving_alert').hide();
        }).always(function(){
          jQuery('.saving_alert').hide();
        });
        rubric.find(":radio:checked,:checkbox:checked").removeAttr("checked");
        rubric.find("div.scores div.selected").removeClass("selected");
        if (!rubric.find("div.scores div.selected").length) {
          rubric.removeClass("scored").addClass("not-scored");
        }
        return this.displayScoresInHeader(rubric);
      };
      TriStateEvaluationTool.prototype.clearTagScore = function (scores) {
        var data, rubric, standardClass, tag, tagId;
        rubric = scores.closest("section");
        scores.find(":radio:checked").removeAttr("checked");
        scores.children(".selected").removeClass("selected");
        tagId = scores.data("tag-id");
        standardClass = scores.data("standard-class");
        data = {"delete": "yes", tag_id: tagId, standard_class: standardClass};
        tag = rubric.find("a.tag[data-tag-id='" + tagId + "'][data-standard-class='" + standardClass + "']");
        tag.find("span.value").text("No score");
        tag.removeClass("scored");
        rcorners(scores.find("label"));
        //$.post(this.evaluateURL, data);
        jQuery('.saving_alert').show();
        jQuery.ajax({
          method: "POST",
          url: this.evaluateURL,
          data: data
        }).done(function( msg ) {
          jQuery('.saving_alert').hide();
        }).always(function(){
          jQuery('.saving_alert').hide();
        });
        if (!rubric.find("div.scores :radio:checked").length) {
          return rubric.removeClass("scored").addClass("not-scored");
        }
      };
      TriStateEvaluationTool.prototype.updateOverallRating = function () {
        var headline, max, min, note, rating, rubric, _i, _len, _ref, _results;
        rating = this.getScoreValue(this.alignmentRigorRubric);
        _ref = this.alignmentRigorRubric.nextAll("section");
        _results = [];
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
          rubric = _ref[_i];
          rubric = $(rubric);
          if (!rubric.is(this.overallRatingRubric)) {
            _results.push(rating += this.getScoreValue(rubric));
          } else {
            note = rubric.find("div.overall-rating-note");
            _results.push((function () {
              var _j, _len1, _ref1, _results1;
              _ref1 = note.find("div.overall-rating-value");
              _results1 = [];
              for (_j = 0, _len1 = _ref1.length; _j < _len1; _j++) {
                headline = _ref1[_j];
                headline = $(headline);
                min = headline.data("min");
                max = headline.data("max");
                if (rating >= min && rating <= max) {
                  headline.show();
                  _results1.push(note.find("span.range").text("" + min + "-" + max));
                } else {
                  _results1.push(headline.hide());
                }
              }
              return _results1;
            })());
          }
        }
        return _results;
      };
      TriStateEvaluationTool.prototype.openCategory = function (category) {
        if (category.hasClass("active")) {
          return;
        }
        return category.addClass("active").siblings().removeClass("active");
      };
      TriStateEvaluationTool.prototype.displayScoresInHeader = function (rubric) {
        var ct, score, scored, scores, _i, _len, _results;
        ct = rubric.find("h1 span.scores");
        if (!ct.length) {
          return;
        }
        scores = [];
        scored = false;
        score = rubric.find("div.scores > div.selected");
        if (score.length) {
          scores.push(score.find("label").text());
          scored = true;
        } else {
          scores.push("NR");
        }
        ct.empty();
        if (scored) {
          _results = [];
          for (_i = 0, _len = scores.length; _i < _len; _i++) {
            score = scores[_i];
            _results.push(ct.append($("<span>" + score + "</span>")));
          }
          return _results;
        }
      };
      TriStateEvaluationTool.prototype.openRubric = function (rubric) {
        this.displayScoresInHeader(this.rubrics.filter(".expanded"));
        if (!rubric.find("div.category.active").length) {
          this.openCategory(rubric.find("div.category").first());
        }
        return TriStateEvaluationTool.__super__.openRubric.call(this, rubric);
      };
      TriStateEvaluationTool.prototype.getScoresFromRubric = function (rubric) {
        var cb, data, rubricId, score, scoreId, scores, standardClass, tagId, _i, _j, _len, _len1, _ref, _ref1;
        data = [];
        rubricId = rubric.data("rubric-id");
        if (rubricId) {
          if (rubric.is(this.overallRatingRubric)) {
            data.push({rubric_id: rubricId, comment: $.trim(rubric.find("div.comment-form textarea").val())});
          } else {
            _ref = rubric.find("div.scores");
            for (_i = 0, _len = _ref.length; _i < _len; _i++) {
              scores = _ref[_i];
              scores = $(scores);
              score = scores.find("div.selected");
              if (!score.length) {
                break;
              }
              scoreId = score.data("score-id");
              data.push({score_id: scoreId, rubric_id: rubricId, checkboxes: (function () {
                  var _j, _len1, _ref1, _results;
                  _ref1 = rubric.find("div.category :checkbox:checked");
                  _results = [];
                  for (_j = 0, _len1 = _ref1.length; _j < _len1; _j++) {
                    cb = _ref1[_j];
                    _results.push($(cb).val());
                  }
                  return _results;
                })(), comment: $.trim(rubric.find("div.comment-form textarea").val())});
            }
          }
        } else {
          _ref1 = rubric.find("div.scores");
          for (_j = 0, _len1 = _ref1.length; _j < _len1; _j++) {
            scores = _ref1[_j];
            scores = $(scores);
            score = scores.find("div.selected");
            if (!score.length) {
              break;
            }
            scoreId = score.data("score-id");
            tagId = scores.data("tag-id");
            standardClass = scores.data("standard-class");
            if (tagId && standardClass) {
              data.push({score_id: scoreId, tag_id: tagId, standard_class: standardClass});
            }
          }
        }
        return data;
      };
      return TriStateEvaluationTool;
    })(BaseEvaluationTool);
    window.TriStateEvaluationTool = TriStateEvaluationTool;
    EvaluationResults = (function () {
      function EvaluationResults() {
        $("span.comment,span[data-tooltip]").qtip({content: {text: function () {
              return $(this).data("comment") || $(this).data("tooltip");
            }}, style: {classes: "comment-popup ui-tooltip-rounded ui-tooltip-shadow"}, position: {my: "top center", at: "bottom center", effect: false}});
        $("a.finalize").click((function (_this) {
          return function (e) {
            e.preventDefault();
            return $("#finalize-form").submit();
          };
        })(this));
      }
      return EvaluationResults;
    })();
    window.EvaluationResults = EvaluationResults;
  }).call(this);
  new EvaluationTool();
});