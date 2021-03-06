<?php global $water_source; ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h3>Update water source</h3>
                            <p>Update a water sales point</p>
                        </div>
                        <div class="panel-body"> 
                            <form method="post" action="" autocomplete="off"> 
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="control-label" for="email">The map below helps you pin the exact position of the water source</label>
                                                <p>Feel free to type in the map coordinates if you're certain of them.</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div id="addRouteCanvas" style="width: 100%;
                                                     height: 400px;"></div> 
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="control-label" for="email">Fill in the water source details</label>
                                                <p>If a water source has no name or ID, the system will allocate one automatically</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="control-label" for="water_source_id">Water Source ID</label>
                                                <input type="text" name="water_source_id" id="water_source_id" class="form-control" placeholder="Water Source ID" value="<?php echo $App->sanitizeVar($water_source, 'water_source_id'); ?>">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="control-label" for="water_source_name">Water Source Name</label>
                                                <input type="text" name="water_source_name" id="water_source_name" class="form-control" placeholder="Water Source Name" value="<?php echo $App->sanitizeVar($water_source, 'water_source_name'); ?>">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="control-label" for="water_source_location">Water Source Location</label>
                                                <input type="text" name="water_source_location" id="water_source_location" class="form-control" placeholder="Water Source Location" value="<?php echo $App->sanitizeVar($water_source, 'water_source_location'); ?>">
                                            </div>
                                        </div>                
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="control-label" for="water_source_coordinates">Water Source Coordinates</label>
                                                <input type="text" name="water_source_coordinates" id="water_source_coordinates" class="form-control" placeholder="Water Source Coordinates" value="<?php echo $App->sanitizeVar($water_source, 'water_source_coordinates'); ?>">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="control-label" for="monthly_charges">Monthly charges</label>
                                                <input type="text" name="monthly_charges" id="monthly_charges" class="form-control" placeholder="Percentage Submitted" value="<?php echo $App->sanitizeVar($water_source, 'monthly_charges'); ?>">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="control-label" for="percentage_saved">Percentage Submitted</label>
                                                <input type="text" name="percentage_saved" id="percentage_saved" class="form-control" placeholder="Percentage Submitted" value="<?php echo $App->sanitizeVar($water_source, 'percentage_saved'); ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr class="dashed">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="control-label" for="water_source_name">Care Takers</label>
                                        <p class="help-block">Select where appropriate to assign.</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <?php
                                    $attendants_ids = array();

                                    $App->con->where('water_source_id', $id_water_source);
                                    $App->con->orderBy('water_source_id', 'ASC');
                                    $a_ids = $App->con->get('water_source_caretakers', null, 'uid');

                                    foreach ($a_ids as $a_id) {
                                        $attendants_ids[] = $a_id['uid'];
                                    }

                                    $query = "SELECT idu,group_id,fname,lname,can_add_water_users FROM " . DB_TABLE_PREFIX . "users "
                                            . "LEFT JOIN " . DB_TABLE_PREFIX . "user_groups ON group_id=id_group "
                                            . "WHERE active=1 ORDER BY fname ASC";

                                    $attendants = $App->con->rawQuery($query);

                                    if (!empty($attendants)) {
                                        foreach ($attendants as $attendant) {
                                            ?>                            
                                            <div class="col-sm-3 col-md-3 col-lg-3 margin-bottom-8">                                       
                                                <label for="attendants_<?php echo $attendant['idu']; ?>" class="checkbox-inline">
                                                    <input type="checkbox" name="attendants[]" class="check" value="<?php echo $attendant['idu']; ?>" id="attendants_<?php echo $attendant['idu']; ?>" <?php echo in_array($attendant['idu'], $attendants_ids) ? 'checked="checked"' : '' ?>>
                                                    <?php echo $attendant['fname'] . " " . $attendant['lname']; ?>
                                                </label> 
                                            </div>
                                            <?php
                                        }
                                    } else {
                                        ?>                              
                                        <div class="col-md-12">
                                            <div class="alert alert-warning">
                                                No attendants have been added so far. Click <strong><a href="?a=add-user">here</a></strong>
                                                to add an attendant.
                                            </div>
                                        </div>                              
                                        <?php
                                    }
                                    ?>   
                                </div>
                                <hr class="dashed"/>                        
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="control-label" for="water_source_name">Treasurers</label>
                                        <p class="help-block">Select where appropriate to assign.</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <?php
                                    $treasurers_ids = array();

                                    $App->con->where('water_source_id', $id_water_source);
                                    $App->con->orderBy('water_source_id', 'ASC');
                                    $a_ids = $App->con->get('water_source_treasurers', null, 'uid');

                                    if (is_array($a_ids)) {
                                        foreach ($a_ids as $a_id) {
                                            $treasurers_ids[] = $a_id['uid'];
                                        }
                                    }

                                    $query = "SELECT idu,group_id,fname,lname,can_add_water_users FROM " . DB_TABLE_PREFIX . "users "
                                            . "LEFT JOIN " . DB_TABLE_PREFIX . "user_groups ON group_id=id_group "
                                            . "WHERE active=1 ORDER BY fname ASC";

                                    $treasurers = $App->con->rawQuery($query);

                                    if (!empty($treasurers)) {
                                        foreach ($treasurers as $treasurer) {
                                            ?>  
                                            <div class="col-sm-3 col-md-3 col-lg-3 margin-bottom-8">                                       
                                                <label for="treasurers_<?php echo $treasurer['idu']; ?>" class="checkbox-inline">
                                                    <input type="checkbox" name="treasurers[]"  class="check" value="<?php echo $treasurer['idu']; ?>" id="treasurers_<?php echo $treasurer['idu']; ?>" <?php echo in_array($treasurer['idu'], $treasurers_ids) ? 'checked="checked"' : '' ?>>
                                                    <?php echo $treasurer['fname'] . " " . $treasurer['lname']; ?>
                                                </label> 
                                            </div>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="alert alert-warning">
                                                    No treasurers have been added so far. Click <strong><a href="?a=add-user">here</a></strong>
                                                    to add an treasurer.
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ?>   
                                </div>
                                <hr class="dashed"/>
                                <div class="row">
                                    <div class="col-md-12">        
                                        <input type="submit" value="Update" name="submit" class="btn btn-primary pull-right">
                                    </div>
                                </div>
                            </form>
                        </div>                       
                    </div>                
                </div>                
            </div>                             
        </div>        
    </div>    
</div>
<script type="text/javascript" src="http://maps.google.com/maps/api/js"></script>
<script type="text/javascript">
    WATER_SOURCE_COORDINATES = [<?php echo isset($water_source['water_source_coordinates']) && !empty($water_source['water_source_coordinates']) ? (is_numeric($water_source['water_source_coordinates'][0]) ? $water_source['water_source_coordinates'] : $CONFIG['default_locale_coordinates']) : $CONFIG['default_locale_coordinates']; ?>];
</script>