<?php
require_once dirname(__FILE__) . '/nette.min.php';
require_once dirname(__FILE__) . '/../AukroApi.php';

//use Nette\Diagnostics\Debugger;

//Debugger::enable();

$client = new AukroAPI\Api('','', '', 228);
$select = $client->getNetteFormHelper()->cartDataSelectBox();

if(isset($_GET['cart_id']))
{
    $form = $client->getNetteFormHelper()->buildFormFields((int) $_GET['cart_id'], 'POST', 'submit.php');
}
else
{
    $form = $client->getNetteFormHelper()->buildFormFields(1811, 'POST', 'submit.php');
}


?>
<!DOCTYPE html>
<html lang="cs">
<head>
	<meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="bootstrap.min.css" type="text/css">
	<title>API aukro NETTE form example</title>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Nette\Forms basic example</h1>
            </div>
        </div>
        <div class="row" style="margin-bottom: 15px;">
            <form  class="form-inline" method="get" action="Nette-basic-example.php">
                <div class="col-lg-5">
                    <button type="submit" class="btn btn-primary">Submit</button>                        
                </div>
                <div class="col-lg-7">
                    <select name="cart_id" class="form-control">
                        <?php 
                        /**
                         * This is only a demonstration. Not to be used in your project, not escaping html!
                         */
                        foreach ($select as $label => $item)
                        {
                            echo '<optgroup label="'.$label.'">';
                            foreach ($item as $key => $value) {
                                if(isset($_GET['cart_id']))
                                {
                                    if($_GET['cart_id'] == $key)
                                    {
                                        echo '<option selected="selected" value="'.$key.'">'.$value.'</option>';
                                        continue;
                                    }
                                }
                                echo '<option value="'.$key.'">'.$value.'</option>';
                            }
                            echo '</optgroup>';
                        }
                        ?>
                    </select>
                </div>   
            </form>
        </div>
        <hr />
        <div class="row">
            <div class="col-lg-12">
                <?php echo $form ?>
            </div>
        </div>
    </div>
</body>
</html>
