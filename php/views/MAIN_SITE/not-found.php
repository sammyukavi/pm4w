<div class="container">
    <section style="min-height: 400px; margin-top: 100px;margin-bottom: 100px;" class="story section has-pattern">
        <div class="row">
            <div class="col-sm-12 text-center margin-top-10">
                <?php $App->printMessage(); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 text-capitalize text-center not-found">
                <h1>
                    <i class="glyphicon glyphicon-question-sign"></i> 
                    <?php echo $App->lang("four_oh_four"); ?>
                </h1>
                <?php echo $App->lang("four_oh_four_message"); ?>            
            </div>
        </div>
    </section>
</div>