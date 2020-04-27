<div style="display:none;">
  <div id="oerdialog" title="Resource Rating">
    <style>
      .ui-dialog {z-index: 5;}
      .ui-dialog .ui-icon-closethick{display:none !important;}
      .ui-dialog .ui-button-text{height: 16px;width: 16px;}
      .comment-popup {max-width: 500px !important;}
    </style>
    <?php
    $url = plugins_url() . '/curriki_manage/';
    $rating = array('-1', '0', '1', '2', '3');
    ?>
    <div class="evaluation-tool authenticated">
      <div class="rubrics" data-evaluate-url="<?php echo admin_url('/admin-ajax.php?action=get_OER_pop_up&resourceid=' . $data->resourceid); ?>">


        <section class="intro expanded">
          <h1><a href="#">Guidelines &amp; Reminders</a></h1>
          <div class="body">
            <!--p class="align-center">
                <a href="" target="_blank" class="btn learn">Learn about evaluating OER</a>
            </p-->
            <div class="guidelines">
              <?php if ($data->title) { ?>
                <p>
                  <strong>Title: </strong> <?php echo $data->title; ?>
                </p>
              <?php } if ($data->description) { ?>
                <p class="highlight">
                  <strong>Description: </strong> <?php echo $data->description; ?>
                </p>

              <?php } if ($data->content) { ?>
                <p>
                  <strong>Contents: </strong> <?php echo $data->content; ?>
                </p>
              <?php } ?>
              <!--ul>
                  <li>Before assessing the alignment of OER to standards, you should have
                      access to the Common Core State Standards (CCSS). These can be found at
                      <a href="" target="_blank">www.corestandards.org</a>.</li>
                  <li>The rubrics are intended to be applied to the smallest, meaningful unit.</li>
                  <li>Each rubric should be scored independently of the others; you may apply
                      up to 7 rubrics to an OER.</li>
                  <li>Mark “N/A” on any rubric that doesn’t apply to the resource you are evaluating.</li>
                  <li>Your review of an object is complete once the ratings are submitted
                      through the “Finalize OER Review” button at the bottom of the
                      last rubric.</li>
                  <li>By using this tool, you agree to license all of your content and
                      comments to the public domain.</li>
              <!--li>
                  <a class="video-link" href="http://www.youtube.com/watch?v=R28mOXH0HKw" target="_blank">Watch an Overview of the Rubrics</a>
              </l>
          </ul-->

            </div>

            <div class="align-center">
              <a href="#" class="rc5 start next">Start Evaluating</a>
            </div>
          </div>
        </section>

        <section class="rubric <?php if (in_array($data->Standardsalignment, $rating) || !empty($data->Standardsalignmentcomment)) echo 'scored'; ?>" data-rubric-id="0">
          <h1><a href="#evaluate:standard">Degree of Alignment to Standards</a></h1>
          <div class="body">
            <div class="description">

              <p>Applies to objects that have suggested alignments to the Common
                Core State Standards and is used to rate the degree of alignment.
                The degree of alignment of both content and performance
                expectations are considered. If appropriate, you may use the
                rubric to rate any of the standards that have been aligned to the
                object you are rating or align additional standards.</p>
            </div>

            <div class="scores selected" data-tag-id="34" data-standard-class="curriculum">

              <div class="s1 <?php if ($data->Standardsalignment == '3') echo 'selected'; ?>" data-score-id="3">
                <label class="rc5">
                  <input value="3" name="score-tag34" type="radio" <?php if ($data->Standardsalignment == '3') echo 'checked'; ?>> 3
                </label>

                <p class="tip">Mouse over for score description</p>
                <div class="description rc5">
                  <p>An object has <em>superior</em> alignment <strong>if BOTH</strong> of the following are true: All of the content and performance expectations in the identified standard are completely addressed by the object. The content and performance expectations of the identified standard are the focus of the object.</p>
                  <p>While some objects may cover a range of standards that could potentially be aligned, for a superior alignment the content and performance expectations must not be a peripheral part of the object.</p>
                </div>
              </div>

              <div class="s2 <?php if ($data->Standardsalignment == '2') echo 'selected'; ?>" data-score-id="2">
                <label class="rc5">
                  <input value="2" name="score-tag34" type="radio" <?php if ($data->Standardsalignment == '2') echo 'checked'; ?>> 2
                </label>

                <div class="description rc5"><p>An object has <em>strong</em> alignment for one of two reasons:</p>
                  <ul>
                    <li>Covers all but minor elements of the standard, cluster or domain.</li>
                    <li>The content and performance expectations of the standard align to a minor part of the object.</li>
                  </ul>
                </div>
              </div>

              <div class="s3 <?php if ($data->Standardsalignment == '1') echo 'selected'; ?>" data-score-id="1">
                <label class="rc5">
                  <input value="1" name="score-tag34" type="radio" <?php if ($data->Standardsalignment == '1') echo 'checked'; ?>> 1
                </label>

                <div class="description rc5">
                  <p>An object has <em>limited</em> alignment if a significant part of the content or performance expectations of the identified standard is not addressed in the object, as long as there is fidelity to the part it does cover.</p>
                  <p><em>For example, an object that aligns to CCSS 2.NBT.2, “Count within 1000; skip-count by 5s, 10s, and 100s,” but only addresses counting numbers to 500 would be considered to have limited alignment.  The object aligns very closely with a limited part of the standard.</em></p>
                </div>
              </div>

              <div class="s4 <?php if ($data->Standardsalignment == '0') echo 'selected'; ?>" data-score-id="0">
                <label class="rc5">
                  <input value="0" name="score-tag34" type="radio" <?php if ($data->Standardsalignment == '0') echo 'checked'; ?>> 0
                </label>

                <div class="description rc5"><p>An object has <em>very weak</em> alignment for either one of two reasons:</p>
                  <ul>
                    <li>The object does not match the intended standards.</li>
                    <li>The object matches only to minimally important aspects of a standard, cluster or domain.</li>
                  </ul>
                  <p>These objects will not typically be useful for instruction of core concepts and performances covered by the standard.</p>
                </div>
              </div>

              <div class="s5 <?php if ($data->Standardsalignment == '-1') echo 'selected'; ?>" data-score-id="-1">
                <label class="rc5">
                  <input type="radio" <?php if ($data->Standardsalignment == '-1') echo 'checked'; ?> value="-1" name="score-rubric1" /> N/A
                </label>

                <div class="description rc5"><p>This rubric is <em>not applicable</em> for an object that is not designed to explain content (e.g. a sheet of mathematical formulae, a map).</p>

                  <p>It may be possible to apply the object in some way that aids a learner’s understanding, but that is beyond any obvious or described purpose of the object.</p>
                </div>
              </div>


            </div>
            <div class="footer selected" data-tag-id="34" data-standard-class="curriculum">

              <a href="#" 
                 class="comment rc5 <?php if (!empty($data->Standardsalignmentcomment)) echo 'checked'; ?>" 
                 data-comment="<?php echo $data->Standardsalignmentcomment; ?>">Comment</a>

              <a href="#" class="dashed clear-score">Clear rating</a>
              <span class="spinner"></span>
              <a href="#" class="next btn btn-orange">Save &amp; Go to Next Rubric</a>
            </div>
          </div>
        </section>

        <section class="rubric <?php if (in_array($data->Subjectmatter, $rating) || !empty($data->Subjectmattercomment)) echo 'scored'; ?>" data-rubric-id="1">
          <h1>
            <a href="#evaluate:rubric1">
              Quality of Explanation of the Subject Matter
            </a>
          </h1>
          <div class="body">
            <div class="description">
              <p>Applies to objects designed to explain subject matter. Used to rate how thoroughly subject matter is explained or otherwise revealed in the resource. Teachers might use object with whole class, small group, or individual student. Students might use this object to self-tutor.</p>
              <!--a href="http://www.youtube.com/watch?v=JISqBa6HAbo" target="_blank" class="video-link">Rubric II</a-->
            </div>

            <div class="scores">

              <div class="s1 <?php if ($data->Subjectmatter == '3') echo 'selected'; ?>" data-score-id="3">
                <label class="rc5">
                  <input type="radio" <?php if ($data->Subjectmatter == '3') echo 'checked'; ?> value="3" name="score-rubric1"/> 3
                </label>

                <p class="tip">Mouse over for score description</p>

                <div class="description rc5"><p>An object is rated <em>superior</em> for content explanation if all of the following are true:</p>

                  <ul>
                    <li>Provides comprehensive information so effectively the target audience can easily understand the subject matter.</li>
                    <li>Connects the subject matter with important associated concepts.</li>
                    <li>Does not need to be augmented with additional explanation or materials.</li>
                    <li>Main ideas of the subject matter and the object are clearly identified for the learner.</li>
                  </ul>
                </div>
              </div>

              <div class="s2 <?php if ($data->Subjectmatter == '2') echo 'selected'; ?>" data-score-id="2">
                <label class="rc5">
                  <input type="radio" <?php if ($data->Subjectmatter == '2') echo 'checked'; ?> value="2" name="score-rubric1"/> 2
                </label>

                <div class="description rc5">
                  <p>An object is rated <em>strong</em> value for content explanation if the content is explained in a way that makes skills, procedures, concepts, and/or information understandable. It falls short of superior in that it does not make clear the important connections between and among the various aspects. </p>
                  <p><em>For example, a lesson on multi-digit addition may focus on the procedure and fail to connect it with place value.</em></p>
                </div>
              </div>

              <div class="s3 <?php if ($data->Subjectmatter == '1') echo 'selected'; ?>" data-score-id="1">
                <label class="rc5">
                  <input type="radio" <?php if ($data->Subjectmatter == '1') echo 'checked'; ?> value="1" name="score-rubric1"/> 1
                </label>

                <div class="description rc5"><p>An object is <em>limited</em> if it explains the subject matter correctly, but in a limited way.</p>

                  <p>This is a cursory treatment of the content that is not developed enough to serve someone attempting to learn the content for the first time.</p>

                  <p>The explanations are not thorough enough to serve as more than a review for most learners.</p>
                </div>
              </div>

              <div class="s4 <?php if ($data->Subjectmatter == '0') echo 'selected'; ?>" data-score-id="0">
                <label class="rc5">
                  <input type="radio" <?php if ($data->Subjectmatter == '0') echo 'checked'; ?> value="0" name="score-rubric1" /> 0
                </label>

                <div class="description rc5"><p>An object is rated <em>very weak or no value</em> if its subject matter explanations are confusing or contain errors.</p>

                  <p>There is little likelihood that this object will contribute to understanding.</p>
                </div>
              </div>

              <div class="s5 <?php if ($data->Subjectmatter == '-1') echo 'selected'; ?>" data-score-id="-1">
                <label class="rc5">
                  <input type="radio" <?php if ($data->Subjectmatter == '-1') echo 'checked'; ?> value="-1" name="score-rubric1" /> N/A
                </label>

                <div class="description rc5"><p>This rubric is <em>not applicable</em> for an object that is not designed to explain content (e.g. a sheet of mathematical formulae, a map).</p>

                  <p>It may be possible to apply the object in some way that aids a learner’s understanding, but that is beyond any obvious or described purpose of the object.</p>
                </div>
              </div>

            </div>
            <div class="footer">
              <a href="#" 
                 class="comment rc5 <?php if (!empty($data->Subjectmattercomment)) echo 'checked'; ?>" 
                 data-comment="<?php echo $data->Subjectmattercomment; ?>">Comment</a>
              <a href="#" class="dashed clear-score">Clear rating</a>
              <span class="spinner"></span>

              <a href="#" class="next btn btn-orange">Save &amp; Go to Next Rubric</a>

            </div>
          </div>
        </section>

        <section class="rubric <?php if (in_array($data->Supportsteaching, $rating) || !empty($data->Supportsteachingcomment)) echo 'scored'; ?>" data-rubric-id="2">
          <h1><a href="#evaluate:rubric2">Utility of Materials Designed to Support Teaching</a></h1>
          <div class="body">
            <div class="description">
              <p>Applies to objects designed to support teachers in planning or presenting subject matter. Primary user would be teacher. Evaluates the potential utility of an object for the majority of instructors at the intended grade level.</p>

              <!--a href="http://www.youtube.com/watch?v=_XBSAv5RZqg" target="_blank" class="video-link">Rubric III</a-->

            </div>

            <div class="scores">

              <div class="s1 <?php if ($data->Supportsteaching == '3') echo 'selected'; ?>" data-score-id="3">
                <label class="rc5">
                  <input type="radio" <?php if ($data->Supportsteaching == '3') echo 'checked'; ?> value="3" name="score-rubric2"/> 3
                </label>

                <p class="tip">Mouse over for score description</p>

                <div class="description rc5"><p>An object is rated <em>superior</em> if all of the following are true:</p>

                  <ul>
                    <li>Materials are comprehensive and easy to understand and use. Includes suggestions for use with a variety of learners.</li>
                    <li>All components are provided and function as intended, including estimate of planning time and materials list.</li>
                    <li>For larger objects, materials facilitate mixed instructional approaches.</li>
                  </ul>
                </div>
              </div>

              <div class="s2 <?php if ($data->Supportsteaching == '2') echo 'selected'; ?>" data-score-id="2">
                <label class="rc5">
                  <input type="radio" <?php if ($data->Supportsteaching == '2') echo 'checked'; ?> value="2" name="score-rubric2" /> 2
                </label>

                <div class="description rc5"><p>An object is rated <em>strong</em> if it offers comprehensive, easy to understand and use materials. Falls short of Superior because either:</p>

                  <ol>
                    <li>Object does not include suggestions for ways to use materials with variety of learners, or</li>
                    <li>core components are underdeveloped in the object.</li>
                  </ol>
                </div>
              </div>

              <div class="s3 <?php if ($data->Supportsteaching == '1') echo 'selected'; ?>" data-score-id="1">
                <label class="rc5">
                  <input type="radio" <?php if ($data->Supportsteaching == '1') echo 'checked'; ?> value="1" name="score-rubric2" /> 1
                </label>

                <div class="description rc5"><p>An object is rated <em>limited</em> if it includes a useful approach or idea to teach an important topic but falls short of a higher rating because either:</p>

                  <ol>
                    <li>the object is missing important elements, e.g. directions for some parts of a lesson are not included or </li>
                    <li>important elements do not do what they should, e.g. directions are unclear. Teachers would need to supplement this object to use it effectively.</li>
                  </ol>
                </div>
              </div>

              <div class="s4 <?php if ($data->Supportsteaching == '0') echo 'selected'; ?>" data-score-id="0">
                <label class="rc5">
                  <input type="radio" <?php if ($data->Supportsteaching == '0') echo 'checked'; ?> value="0" name="score-rubric2" /> 0
                </label>

                <div class="description rc5"><p>An object is rated <em>very weak</em> or no value for instructional purposes if confusing, contains errors, is missing many important elements, or is simply not useful for some other reason.</p>
                </div>
              </div>

              <div class="s5 <?php if ($data->Supportsteaching == '-1') echo 'selected'; ?>" data-score-id="-1">
                <label class="rc5">
                  <input type="radio" <?php if ($data->Supportsteaching == '-1') echo 'checked'; ?> value="-1" name="score-rubric2" /> N/A
                </label>

                <div class="description rc5"><p>This rubric is <em>not applicable</em> for an object that is not designed as a teacher’s instructional tool. </p>

                  <p>An enterprising educator may find an application for such an object during a lesson, but that would be an unintended use.</p>
                </div>
              </div>

            </div>
            <div class="footer">
              <a href="#" 
                 class="comment rc5 <?php if (!empty($data->Supportsteachingcomment)) echo 'checked'; ?>" 
                 data-comment="<?php echo $data->Supportsteachingcomment; ?>">Comment</a>
              <a href="#" class="dashed clear-score">Clear rating</a>
              <span class="spinner"></span>

              <a href="#" class="next btn btn-orange">Save &amp; Go to Next Rubric</a>

            </div>
          </div>
        </section>

        <section class="rubric <?php if (in_array($data->Assessmentsquality, $rating) || !empty($data->Assessmentsqualitycomment)) echo 'scored'; ?>" data-rubric-id="3">
          <h1><a href="#evaluate:rubric3">Quality of Assessments</a>

          </h1>
          <div class="body">
            <div class="description">
              <p>Applies to objects designed to determine what a student knows before, during, or after a topic is taught. When many assessments are included in one object, the rubric is applied to the entire set.</p>
              <!--a href="http://www.youtube.com/watch?v=U5vP5miQKm8" target="_blank" class="video-link">Rubric IV</a-->
            </div>

            <div class="scores">

              <div class="s1 <?php if ($data->Assessmentsquality == '3') echo 'selected'; ?>" data-score-id="3">
                <label class="rc5">
                  <input type="radio" <?php if ($data->Assessmentsquality == '3') echo 'checked'; ?> value="3" name="score-rubric3" /> 3
                </label>

                <p class="tip">Mouse over for score description</p>

                <div class="description rc5"><p>An object is rated <em>superior</em> if all of the following are true:</p>

                  <ul>
                    <li>All concepts/skills assessed align clearly to content and performance expectations and included in the object’s material.</li>
                    <li>Critical aspects are targeted and given appropriate weight/attention. </li>
                    <li>Types of assessments offered require clear demonstration of proficiency. </li>
                    <li>Any level of difficulty results from complexity of the subject area/cognitive demand, rather than unrelated issues (e.g. complex vocabulary).</li>
                  </ul>
                </div>
              </div>

              <div class="s2 <?php if ($data->Assessmentsquality == '2') echo 'selected'; ?>" data-score-id="2">
                <label class="rc5">
                  <input type="radio" <?php if ($data->Assessmentsquality == '2') echo 'checked'; ?> value="2" name="score-rubric3" /> 2
                </label>

                <div class="description rc5"><p>An object is rated <em>strong</em> if it assesses all or nearly all of the content and performance expectations intended, but the assessment mode(s) offered fail(s) to require that the student demonstrate proficiency in the intended concept/skill.</p>
                </div>
              </div>

              <div class="s3 <?php if ($data->Assessmentsquality == '1') echo 'selected'; ?>" data-score-id="1">
                <label class="rc5">
                  <input type="radio" <?php if ($data->Assessmentsquality == '1') echo 'checked'; ?> value="1" name="score-rubric3" /> 1
                </label>

                <div class="description rc5"><p>An object is rated <em>limited</em> if it assesses some of the content or performance expectations intended, as stated or implicit in the object, but omits some important content or performance expectations.</p>
                </div>
              </div>

              <div class="s4 <?php if ($data->Assessmentsquality == '0') echo 'selected'; ?>" data-score-id="0">
                <label class="rc5">
                  <input type="radio" <?php if ($data->Assessmentsquality == '0') echo 'checked'; ?> value="0" name="score-rubric3" /> 0
                </label>

                <div class="description rc5"><p>An object is rated <em>very weak or no value</em> if its assessments contain significant errors, do not assess important content or performance, are written in a way that is confusing to students or are unsound for other reasons.</p>
                </div>
              </div>

              <div class="s5 <?php if ($data->Assessmentsquality == '-1') echo 'selected'; ?>" data-score-id="-1">
                <label class="rc5">
                  <input type="radio" <?php if ($data->Assessmentsquality == '-1') echo 'checked'; ?> value="-1" name="score-rubric3" /> N/A
                </label>

                <div class="description rc5"><p>This rubric is deemed <em>not applicable</em> for an object that is not designed to have an assessment component.</p>

                  <p>Even if one might imagine ways an object could be used for assessment purposes, if it is not the intended purpose, not applicable is the appropriate score.</p>
                </div>
              </div>

            </div>
            <div class="footer">
              <a href="#" 
                 class="comment rc5 <?php if (!empty($data->Assessmentsqualitycomment)) echo 'checked'; ?>" 
                 data-comment="<?php echo $data->Assessmentsqualitycomment; ?>">Comment</a>
              <a href="#" class="dashed clear-score">Clear rating</a>
              <span class="spinner"></span>

              <a href="#" class="next btn btn-orange">Save &amp; Go to Next Rubric</a>

            </div>
          </div>
        </section>

        <section class="rubric <?php if (in_array($data->Interactivityquality, $rating) || !empty($data->Interactivityqualitycomment)) echo 'scored'; ?>" data-rubric-id="4">
          <h1><a href="#evaluate:rubric4">Quality of Technological Interactivity</a>

          </h1>
          <div class="body">
            <div class="description">
              <p>Applies to objects that have a technology-based interactive component. Used to rate degree and quality of an object’s interactivity. Interactivity broadly means that the object responds to the user – the object behaves differently based on what the user does. This is not a rating for technology in general, but for technological interactivity.</p>
              <!--a href="http://www.youtube.com/watch?v=stqsWSvr8mI" target="_blank" class="video-link">Rubric V</a-->
            </div>

            <div class="scores">

              <div class="s1 <?php if ($data->Interactivityquality == '3') echo 'selected'; ?>" data-score-id="3">
                <label class="rc5">
                  <input type="radio" <?php if ($data->Interactivityquality == '3') echo 'checked'; ?> value="3" name="score-rubric4" /> 3
                </label>

                <p class="tip">Mouse over for score description</p>

                <div class="description rc5"><p>An object, or interactive component of an object, is rated <em>superior</em> if all are true:</p>

                  <ul>
                    <li>Responsive to student input in a way that creates an individualized learning experience, adapts to the learner based on what s/he does, or allows flexibility or individual control during the learning experience.</li>
                    <li>Interactive element is purposeful and directly related to learning.</li>
                    <li>The object is well-designed and easy-to-use, encouraging learner use.</li>
                    <li>Appears to function flawlessly on the intended platform.</li>
                  </ul>
                </div>
              </div>

              <div class="s2 <?php if ($data->Interactivityquality == '2') echo 'selected'; ?>" data-score-id="2">
                <label class="rc5">
                  <input type="radio" <?php if ($data->Interactivityquality == '2') echo 'checked'; ?> value="2" name="score-rubric4" /> 2
                </label>

                <div class="description rc5"><p>An object, or interactive component of an object, is rated <em>strong</em> if the object has an interactive feature that is purposeful and directly related to learning, but does not provide an individualized learning experience.</p>

                  <p>Strong interactive objects must also be well designed, easy-to-use, and function flawlessly. Some strong interactive elements (e.g. earning points or levels) might increase motivation and understanding by rewarding or entertaining, and may extend the time of user engagement.</p>
                </div>
              </div>

              <div class="s3 <?php if ($data->Interactivityquality == '1') echo 'selected'; ?>" data-score-id="1">
                <label class="rc5">
                  <input type="radio" <?php if ($data->Interactivityquality == '1') echo 'checked'; ?> value="1" name="score-rubric4" /> 1
                </label>

                <div class="description rc5"><p>An object, or interactive component of an object, is rated <em>limited</em> if it does not relate to the content and may detract from the learning experience.</p>

                  <p>Although it may slightly increase motivation, it is unlikely the interactive feature will increase understanding or extend the time a user engages with the content.</p>
                </div>
              </div>

              <div class="s4 <?php if ($data->Interactivityquality == '0') echo 'selected'; ?>" data-score-id="0">
                <label class="rc5">
                  <input type="radio" <?php if ($data->Interactivityquality == '0') echo 'checked'; ?> value="0" name="score-rubric4" /> 0
                </label>

                <div class="description rc5"><p>An object, or interactive component of an object, is rated <em>very weak or no value</em> if it has interactive features that are poorly conceived and/or executed.</p>

                  <p>The interactive features might fail to operate as intended, or the “bells and whistles” distract the user or unnecessarily take up user time.</p>
                </div>
              </div>

              <div class="s5 <?php if ($data->Interactivityquality == '-1') echo 'selected'; ?>" data-score-id="-1">
                <label class="rc5">
                  <input type="radio" <?php if ($data->Interactivityquality == '-1') echo 'checked'; ?> value="-1" name="score-rubric4" /> N/A
                </label>

                <div class="description rc5"><p>This rubric is deemed <em>not applicable</em> for an object that does not contain an interactive element.</p>
                </div>
              </div>

            </div>
            <div class="footer">
              <a href="#" 
                 class="comment rc5 <?php if (!empty($data->Interactivityqualitycomment)) echo 'checked'; ?>" 
                 data-comment="<?php echo $data->Interactivityqualitycomment; ?>">Comment</a>
              <a href="#" class="dashed clear-score">Clear rating</a>
              <span class="spinner"></span>

              <a href="#" class="next btn btn-orange">Save &amp; Go to Next Rubric</a>

            </div>
          </div>
        </section>

        <section class="rubric <?php if (in_array($data->Instructionalquality, $rating) || !empty($data->Instructionalqualitycomment)) echo 'scored'; ?>" data-rubric-id="5">
          <h1><a href="#evaluate:rubric5">Quality of Instructional and Practice Exercises</a>

          </h1>
          <div class="body">
            <div class="description">
              <p>Applies to objects that contain exercises to help foundational skills and procedures become routine. When concepts and skills are introduced, providing a sufficient number of exercises to support skill acquisition is critical. However when integrating skills in complex tasks, as few as one or two may be sufficient. A group of practice exercises is treated as a single object, with the rubric applied to the entire set.</p>
              <!--a href="http://www.youtube.com/watch?v=54NGER4-wJY" target="_blank" class="video-link">Rubric VI</a-->
            </div>

            <div class="scores">

              <div class="s1 <?php if ($data->Instructionalquality == '3') echo 'selected'; ?>" data-score-id="3">
                <label class="rc5">
                  <input type="radio" <?php if ($data->Instructionalquality == '3') echo 'checked'; ?> value="3" name="score-rubric5" /> 3
                </label>

                <p class="tip">Mouse over for score description</p>

                <div class="description rc5"><p>An object is rated <em>superior</em> if <em>ALL</em> of the following are true:</p>

                  <ul>
                    <li>Offers more exercises than needed, for the average student, to support mastery of targeted skills, as stated or implied in the object. (For complex tasks, one or two rich tasks may be more than enough.)</li>
                    <li>Exercises are clearly written and supported by accurate answer keys/scoring guidelines.</li>
                    <li>There are a variety of exercise types and/or formats, as appropriate, and/or a variety of skills integrated in the practice of more complex tasks.</li>
                  </ul>
                </div>
              </div>

              <div class="s2 <?php if ($data->Instructionalquality == '2') echo 'selected'; ?>" data-score-id="2">
                <label class="rc5">
                  <input type="radio" <?php if ($data->Instructionalquality == '2') echo 'checked'; ?> value="2" name="score-rubric5" /> 2
                </label>

                <div class="description rc5"><p>An object is rated <em>strong</em> if it offers only a sufficient number of well-written exercises to support mastery of targeted skills, which are supported by accurate answer keys, but provide little variety of exercise types or formats in more complex or integrated exercises.</p>
                </div>
              </div>

              <div class="s3 <?php if ($data->Instructionalquality == '1') echo 'selected'; ?>" data-score-id="1">
                <label class="rc5">
                  <input type="radio" <?php if ($data->Instructionalquality == '1') echo 'checked'; ?> value="1" name="score-rubric5" /> 1
                </label>

                <div class="description rc5"><p>An object is rated <em>limited</em> if it includes some, but too few exercises, without answer keys, and with no variation in exercise type, format, or integrated skills.</p>
                </div>
              </div>

              <div class="s4 <?php if ($data->Instructionalquality == '0') echo 'selected'; ?>" data-score-id="0">
                <label class="rc5">
                  <input type="radio" <?php if ($data->Instructionalquality == '0') echo 'checked'; ?> value="0" name="score-rubric5" /> 0
                </label>

                <div class="description rc5"><p>An object is rated <em>very weak or no value</em> if exercises provided do not do not facilitate mastery of the targeted skills, contains errors, or is unsound for other reasons.</p>
                </div>
              </div>

              <div class="s5 <?php if ($data->Instructionalquality == '-1') echo 'selected'; ?>" data-score-id="-1">
                <label class="rc5">
                  <input type="radio" <?php if ($data->Instructionalquality == '-1') echo 'checked'; ?> value="-1" name="score-rubric5" /> N/A
                </label>

                <div class="description rc5"><p>This rubric is <em>not applicable</em> to an object that is not designed to provide learner practice.</p>

                  <p>Even if one might imagine ways the object could be used as practice, not applicable is the appropriate score if that is not the intention of the object.</p>
                </div>
              </div>

            </div>
            <div class="footer">
              <a href="#" 
                 class="comment rc5 <?php if (!empty($data->Instructionalqualitycomment)) echo 'checked'; ?>" 
                 data-comment="<?php echo $data->Instructionalqualitycomment; ?>">Comment</a>
              <a href="#" class="dashed clear-score">Clear rating</a>
              <span class="spinner"></span>
              <a href="#" class="next btn btn-orange">Save &amp; Go to Next Rubric</a>
            </div>
          </div>
        </section>

        <section class="rubric <?php if (in_array($data->Deeperlearning, $rating) || !empty($data->Deeperlearningcomment)) echo 'scored'; ?>" data-rubric-id="6">
          <h1><a href="#evaluate:rubric6">Opportunities for Deeper Learning</a>
            <a href="" target="_blank" class="right"></a>
          </h1>
          <div class="body">
            <div class="description">
              <p>Applies to objects that engage learners to: Think critically and solve complex problems. Reason abstractly. Work collaboratively. Learn how to learn. Communicate effectively. Construct viable arguments and critique the reasoning of others. Apply discrete knowledge and skills to real-world situations. Construct, use, or analyze models.</p>
              <!--a href="http://www.youtube.com/watch?v=576aFmVWCVU" target="_blank" class="video-link">Rubric VII</a-->
            </div>

            <div class="scores">

              <div class="s1 <?php if ($data->Deeperlearning == '3') echo 'selected'; ?>" data-score-id="3">
                <label class="rc5">
                  <input type="radio" <?php if ($data->Deeperlearning == '3') echo 'checked'; ?> value="3" name="score-rubric6" /> 3
                </label>

                <p class="tip">Mouse over for score description</p>

                <div class="description rc5"><p>An object is rated <em>superior</em> when <em>ALL</em> the following are true:</p>

                  <ul>
                    <li><em>Three</em> or more of the deeper learning skills identified in this rubric are required.</li>
                    <li>A range of cognitive demand appropriate to and supportive of material targeted are offered. </li>
                    <li>Appropriate scaffolding and direction are provided.</li>
                  </ul>
                </div>
              </div>

              <div class="s2 <?php if ($data->Deeperlearning == '2') echo 'selected'; ?>" data-score-id="2">
                <label class="rc5">
                  <input type="radio" <?php if ($data->Deeperlearning == '2') echo 'checked'; ?> value="2" name="score-rubric6" /> 2
                </label>

                <div class="description rc5"><p>An object is rated <em>strong</em> if it contains <em>one or two</em> deeper learning skills identified in this rubric, such as an object that involves a complex problem that requires abstract reasoning skills to reach a solution.</p>
                </div>
              </div>

              <div class="s3 <?php if ($data->Deeperlearning == '1') echo 'selected'; ?>" data-score-id="1">
                <label class="rc5">
                  <input type="radio" <?php if ($data->Deeperlearning == '1') echo 'checked'; ?> value="1" name="score-rubric6" /> 1
                </label>

                <div class="description rc5"><p>An object is rated <em>limited</em> if it includes one deeper learning skill identified in the rubric, but is missing clear guidance on how to tap into various aspects of deeper learning.</p>

                  <p><em>For example, an object might include a provision for learners to collaborate, but the process and product are unclear.</em></p>
                </div>
              </div>

              <div class="s4 <?php if ($data->Deeperlearning == '0') echo 'selected'; ?>" data-score-id="0">
                <label class="rc5">
                  <input type="radio" <?php if ($data->Deeperlearning == '0') echo 'checked'; ?> value="0" name="score-rubric6" /> 0
                </label>

                <div class="description rc5"><p>An object is rated <em>very weak</em> if it appears to be designed to provide some of the deeper learning opportunities but it is not useful in its present form.</p>

                  <p>Might be based on poorly formulated problems and/or unclear directions, making it unlikely that this lesson or activity will lead to skills like critical thinking, abstract reasoning, constructing arguments, or modeling.</p>
                </div>
              </div>

              <div class="s5 <?php if ($data->Deeperlearning == '-1') echo 'selected'; ?>" data-score-id="-1">
                <label class="rc5">
                  <input type="radio" <?php if ($data->Deeperlearning == '-1') echo 'checked'; ?> value="-1" name="score-rubric6" /> N/A
                </label>

                <div class="description rc5"><p>This rubric is <em>not applicable</em> to an object that does not appear to be designed to provide the opportunity for deeper learning, even though one might imagine how it could be used to do so.</p>
                </div>
              </div>

            </div>
            <div class="footer">
              <a href="#" 
                 class="comment rc5 <?php if (!empty($data->Deeperlearningcomment)) echo 'checked'; ?>" 
                 data-comment="<?php echo $data->Deeperlearningcomment; ?>">Comment</a>
              <a href="#" class="dashed clear-score">Clear rating</a>
              <span class="spinner"></span>
              <a href="<?php echo site_url().'/oer/'.$_GET['pageurl']."?action=review_result"; ?>" class="save btn btn-orange">Save &amp; View Results</a>

            </div>
          </div>
        </section>

        <div id="comment-form">
          <textarea name="comment" title="Comment"></textarea>
          <footer>
            <a href="#" class="clear-comment dashed">Clear comment</a>
            <a href="#" class="save btn btn-orange">Save</a>
          </footer>
        </div>
      </div>
      <br/><br/>
      <footer class="global">
        <!--a class="finalize btn btn-orange" href="<?php echo get_bloginfo('url') . '/?rid=' . $data->resourceid; ?>" target="_blank"> &nbsp;&nbsp;Review Resource&nbsp;&nbsp; </a-->
        <a href="<?php echo site_url().'/oer/'.$_GET['pageurl']."?action=review_result"; ?>" class="results btn"> &nbsp;&nbsp;View Results&nbsp;&nbsp; </a>
        <div class="saving_alert" style="float: right;margin-right: 10px;display: none;"><strong>Saving ....</strong></div>
      </footer>

    </div>
  </div>
      <!--<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
      <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.15/jquery-ui.min.js"></script>
      <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
      <script type="text/javascript" src="<?php echo $url; ?>assets/jquery.tmpl.js"></script>
      <script type="text/javascript" src="<?php echo $url; ?>assets/jquery.qtip.js"></script>
      <script type="text/javascript" src="<?php echo $url; ?>assets/oer-evaluation-tool.js" charset="utf-8"></script>-->

  <link rel="stylesheet" href="<?php echo plugins_url(); ?>/curriki_manage/assets/layouts.css" type="text/css" charset="utf-8" />
  <link rel="stylesheet" href="<?php echo plugins_url(); ?>/curriki_manage/assets/reset.css" type="text/css" media="all" charset="utf-8" />

  <?php
  wp_enqueue_script('modernizr', $url . "assets/modernizr.custom.js", array('jquery'), false, true);
  wp_enqueue_script('tmpl', $url . "assets/jquery.tmpl.js", array('qtip'), false, true);
  wp_enqueue_script('EvaluationTool', $url . "assets/oer-evaluation-tool.js", array('tmpl'), false, true);
  ?>

  <script>
    var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    var baseurl = '<?php echo get_bloginfo('url'); ?>/';

    var open_review_dialog = function () {
      jQuery("#oerdialog").dialog({
        modal: true,
        width: 630,
        height: 650
      });
    }

    jQuery(open_review_dialog);

  </script>

</div>