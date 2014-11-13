<?php

include_once '../../config.php';
include_once 'includes/header.php';

$request = [
    'base_question_type' => [
        'hidden'   => [],
        'shown'    => [],
        'defaults' => [
            'stimulus' => 'this is a default stimulus'
        ],
        'attributes_asset_enabled' => false,
        // Default accordion groups
        'attribute_groups' => [
            [
                'reference' => 'basic',
                'name'      => 'Basic'
            ],
            [
                'reference' => 'formatting',
                'name'      => 'Formatting'
            ],
            [
                'reference' => 'validation',
                'name'      => 'Validation'
            ],
            [
                'reference' => 'metadata',
                'name'      => 'Metadata'
            ],
            [
                'reference' => 'advanced',
                'name'      => 'Advanced'
            ]
        ],
    ],
    'ui' => [
        'public_methods' => [],
        'layout'             => '2-column',
        'question_tiles'     => false,
        'documentation_link' => false,
        'change_button'      => true,
        'source_button'      => true,
        'fixed_preview'      => true
    ],
    'template_defaults' => true,
    'widget_type'       => 'response',
    'configuration'     => [
        'questionsApiVersion' => 'v2'
    ]
];

include_once 'utils/settings-override.php';

$signedRequest = json_encode($request);

?>

<div class="jumbotron section">
    <div class="toolbar">
        <ul class="list-inline">
            <li data-toggle="tooltip" data-original-title="Customise API Settings"><a href="#" class="text-muted" data-toggle="modal" data-target="#settings"><span class="glyphicon glyphicon-list-alt"></span></a></li>
            <li data-toggle="tooltip" data-original-title="Preview API Initialisation Object"><a href="#"  data-toggle="modal" data-target="#initialisation-preview"><span class="glyphicon glyphicon-search"></span></a></li>
            <li data-toggle="tooltip" data-original-title="Visit the documentation"><a href="http://docs.learnosity.com/questioneditorapi/" title="Documentation"><span class="glyphicon glyphicon-book"></span></a></li>
            <li data-toggle="tooltip" data-original-title="Toggle product overview box"><a href="#"><span class="glyphicon glyphicon-chevron-up jumbotron-toggle"></span></a></li>
        </ul>
    </div>
    <div class="overview">
        <h1>Question Editor API – Customise</h1>
        <p><a href="#" class="text-muted" data-toggle="modal" data-target="#settings">Customise</a> the Question Editor to suit your individual needs.<p>
    </div>
</div>

<div class="section">
    <!-- Container for the question editor api to load into -->
    <script src="//questioneditor.learnosity.com?v2"></script>
    <div class="learnosity-question-editor"></div>
</div>

<script>
    var init, questionEditorApp;

    init = <?php echo $signedRequest; ?>;

    questionEditorApp = LearnosityQuestionEditor.init(init);
</script>

<?php
    include_once 'views/modals/settings-questioneditor.php';
    include_once 'views/modals/initialisation-preview.php';
    include_once 'views/modals/asset-upload.php';
    include_once 'includes/footer.php';
