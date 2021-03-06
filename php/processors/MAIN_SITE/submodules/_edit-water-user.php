<?php

$errors = array();

$id_user = $App->getValue('id');
$App->con->where('id_user', $id_user);
$customer = $App->con->getOne('water_users');

if (isset($_POST['submit'])) {
    if ($App->can_edit_water_users) {
        if (isset($customer['id_user'])) {
            $params['id_user'] = $customer['id_user'];
            $params['fname'] = $App->postValue('fname');
            $params['lname'] = $App->postValue('lname');
            $params['pnumber'] = $App->postValue('pnumber');
            $params['water_source_id'] = $App->postValue('water_source_id');
            $params['added_by'] = $App->postValue('uid');
            $params['last_updated'] = $App->getCurrentDateTime();
            if (empty($params['added_by'])) {
                $params['added_by'] = $App->user->uid;
            }
            if (empty($params['fname'])) {
                $errors[] = "First name is required";
            }
            if (empty($params['lname'])) {
                $errors[] = "Last name is required";
            }

            if (!empty($params['pnumber'])) {
                $params['pnumber'] = $App->autoCorrectPnumber($params['pnumber']);
            }

            if (!empty($params['pnumber']) && ($App->isTaken('user_pnumber', $params['pnumber'])) && $customer['pnumber'] !== $params['pnumber']) {
                $errors[] = "That phone number is already in use";
            } elseif (!empty($params['pnumber']) && !$App->isValid('pnumber', $params['pnumber'])) {
                $errors[] = "Please use a valid phone number in the format shown";
            }

            if (!$App->checkIFExists("water_source_caretakers", array(
                        'water_source_id' => $params['water_source_id'],
                        'uid' => $params['added_by'])) && !$App->checkIFExists("water_source_treasurers", array(
                        'water_source_id' => $params['water_source_id'],
                        'uid' => $params['added_by']))
            ) {
                $errors[] = "The user you selected is not authorised to add users for this water source. If you feel this is an error, please consult your administrator.";
            }

            if (empty($errors)) {
                if ($App->saveWaterUser($params)) {
                    $App->setSessionMessage("Water User updated", SUCCESS_STATUS_CODE);
                    $App->navigate('/manage/water-users/');
                } else {
                    $App->setSessionMessage("An error occured updating the customer. Please try again later");
                }
            }
        } else {
            $App->setSessionMessage("That water user does not exist");
        }
    } else {
        $App->setSessionMessage("You do not have the required rights to perform this action");
    }
}



if (!isset($customer['id_user'])) {
    $App->setSessionMessage("User does not exist");
    $App->navigate('/manage/water-users');
}

foreach ($errors as $error) {
    $App->setSessionMessage($error);
}