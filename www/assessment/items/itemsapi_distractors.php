<?php

include_once '../../config.php';
include_once 'includes/header.php';

use LearnositySdk\Request\Init;
use LearnositySdk\Utils\Uuid;

$security = array(
    'consumer_key' => $consumer_key,
    'domain'       => $domain,
    'timestamp'    => $timestamp
);

$request = array(
    'user_id'        => $studentid,
    'rendering_type' => 'inline',
    'name'           => 'Items API demo - Inline Activity.',
    'state'          => 'initial',
    'activity_id'    => 'itemsinlinedemo',
    'session_id'     => Uuid::generate(),
    'course_id'      => $courseid,
    'items'          => array('act1','act2','act3','act4','act5','act6'),
    'type'           => 'submit_practice',
    'config'         => array(
        'renderSubmitButton'  => true,
        'questionsApiVersion' => 'v2'
    )
);

$Init = new Init('items', $security, $consumer_secret, $request);
$signedRequest = $Init->generate();

?>

<div class="jumbotron section">
    <div class="pull-right toolbar">
        <ul class="list-inline">
            <li data-toggle="tooltip" data-original-title="Visit the documentation"><a href="http://docs.learnosity.com/itemsapi/" title="Documentation"><span class="glyphicon glyphicon-book"></span></a></li>
        </ul>
    </div>
    <h1>Items API – Inline</h1>
    <p>Display items from the Learnosity Item Bank in no time with the Items API.  The Items API builds on the Questions API's power and makes it quicker to integrate.<p>
</div>

<div class="section">
    <p>
        <span class="learnosity-item" data-reference="act1"></span>
        <span class="learnosity-item" data-reference="act2"></span>
        <span class="learnosity-item" data-reference="act3"></span>
        <span class="learnosity-item" data-reference="act4"></span>
        <span class="learnosity-item" data-reference="act5"></span>
        <span class="learnosity-item" data-reference="act6"></span>
        <span class="learnosity-submit-button"></span>
    </p>
</div>

<!-- Container for the items api to load into -->
<script src="//items.learnosity.com/"></script>
<script>
    var eventOptions = {
        readyListener: function () {

            $.each(itemsInstance.questions(), function (index, question) {
                question.on('validated', function () {
                    var outputHTML = '';
                    var map, qid;
                    if (question.isValid()) {
                        return;
                    }
                    if (question.getResponse().type === 'array') {
                        map = question.mapValidationMetadata('distractor_rationale_response_level');
                        $.each(map.incorrect, function (i, data) {

                            // Each item in the `map.incorrect` array is an
                            // object that contains a `value` property that
                            // holds the response value; an `index` property
                            // that refers to the shared index of both the
                            // response area and the metadata; and a `metadata`
                            // property containing the metadata value.
                            var distractor = data.metadata;

                            outputHTML += '<li>' + distractor + '</li>';
                        });
                        if (outputHTML) {
                            outputHTML = '<ul>' + outputHTML + '</ul>';
                        }
                    } else {
                        outputHTML = question.getMetadata().distractor_rationale;
                    }
                    if (!outputHTML) {
                        outputHTML = 'Have you answered all possible responses?';
                    }
                    qid = question.getQuestion().response_id;
                    renderDistractor(qid, outputHTML);
                    MathJax.Hub.Queue(['Typeset', MathJax.Hub, qid]);
                });
            });

            $.each(itemsInstance.questions(), function (index, question) {
                question.on('changed', function () {
                    removeDistractor(this.getQuestion().response_id);
                });
            });

        }
    };
    var itemsInstance = LearnosityItems.init(<?php echo $signedRequest; ?>, eventOptions);

    // Host page rendering logic
    function renderDistractor (id, content) {
        var template;
        if ($("#" + id + "_distractor").length) {
            $("#" + id + "_distractor").html(content).fadeIn();
        } else {
            template = "<div id=\"" + id + "_distractor\" class=\"distractor-rationale alert alert-danger\">" + content + "</div>";
            $("#" + id).append(template);
        }
    }
    function removeDistractor (id) {
        $("#" + id + '_distractor').fadeOut();
    }

</script>

<style>
    .distractor-rationale { margin: 6px 0 24px; }
</style>

<?php
    include_once 'views/modals/initialisation-preview.php';
    include_once 'includes/footer.php';
