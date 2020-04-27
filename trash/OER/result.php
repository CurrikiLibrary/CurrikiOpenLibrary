<!DOCTYPE html>
<html lang="en" class="no-js">
    <head>
        <meta charset="utf-8"/>
        <link rel="stylesheet" href="assets/layouts.css" type="text/css" charset="utf-8" />
        <link rel="stylesheet" href="assets/reset.css" type="text/css" media="all" charset="utf-8" />
        <script type="text/javascript" src="assets/modernizr.custom.js"></script>
    </head>  
    <body class="evaluation-tool authenticated">
        <div class="evaluation-results">
            <figure>
                <figcaption>
                    Results
                </figcaption>
                <table>
                    <thead>
                        <tr>
                            <th colspan="2" width="50%">Rubric</th>
                            <th>Your Score</th>
                            <th>Average Score</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="first" colspan="2">Degree of Alignment</td>
                            <td><span class="na">N/A</span></td>
                            <td><span class="na">N/A</span></td>
                        </tr>
                        <tr>
                            <td class="first" colspan="2">Quality of Explanation of the Subject Matter</td>
                            <td><span class="nr"></span></td>
                            <td><span class="nr"></span></td>
                        </tr>
                        <tr>
                            <td class="first" colspan="2">Utility of Materials Designed to Support Teaching</td>
                            <td><span class="nr"></span></td>
                            <td><span class="nr"></span></td>
                        </tr>
                        <tr>
                            <td class="first" colspan="2">Quality of Assessments</td>
                            <td><span class="nr"></span></td>
                            <td><span class="nr"></span></td>
                        </tr>

                        <tr>
                            <td class="first" colspan="2">Quality of Technological Interactivity</td>
                            <td><span class="nr"></span></td>
                            <td><span class="nr"></span></td>
                        </tr>

                        <tr>
                            <td class="first" colspan="2">Quality of Instructional and Practice Exercises</td>
                            <td><span class="s0">0</span></td>
                            <td><span class="s0">0</span></td>
                        </tr>
                        <tr>
                            <td class="first" colspan="2">Opportunities for Deeper Learning</td>
                            <td><span class="nr"></span></td>
                            <td><span class="nr"></span></td>
                        </tr>
                    </tbody>
                </table>
            </figure>
            
            <form id="finalize-form" method="post" class="formatted"
                  action="https://staging.oercommons.org/evaluate/35/71195/finalize">
            </form>
            
            <div class="footer">
                <a class="back btn btn-gray-plain"
                   href="https://staging.oercommons.org/evaluate/35/71195#rubric1rubric">
                    Go Back &amp; Change Your Scores
                </a>
                <a class="finalize btn btn-orange" href="#"> Finalize OER Review </a>

            </div>
        </div>

        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.15/jquery-ui.min.js"></script>

        <!--script type="text/javascript" src="assets/jquery.tmpl.js"></script-->
        <script type="text/javascript" src="assets/jquery.qtip.js"></script>
        <script type="text/javascript" src="assets/oer-evaluation-tool.js" charset="utf-8"></script>
        <script>
            $(function () {
                new EvaluationTool();
            });
        </script>
    </body>
</html>