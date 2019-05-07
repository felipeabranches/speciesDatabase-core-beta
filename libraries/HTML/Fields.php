<?php
class Fields
{
    public function __construct()
    {
        //if ($debug_mode) echo 'A classe "', __CLASS__, '" foi instanciada!<br />';
    }

    public function __destruct()
    {
        //if ($debug_mode) echo 'A classe "', __CLASS__, '" foi destruída!<br />';
    }

    function checkbox ($label, $field, $value, $attributes)
    {
        ?>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" name="<?php echo $field; ?>" id="<?php echo $field; ?>" value="<?php echo $value; ?>"<?php if ($attributes) echo ' '.$attributes; ?>>
            <label for="<?php echo $field; ?>" class="form-check-label"><?php echo $label; ?></label>
        </div>
        <?php
    }

    function date ($label, $field, $value, $placeholder, $attributes)
    {
        ?>
        <div class="form-group">
            <label for="<?php echo $field; ?>"><?php echo $label; ?><?php if ($attributes == 'required') echo ' *'; ?></label>
            <input type="date" class="form-control" name="<?php echo $field; ?>" id="<?php echo $field; ?>" value="<?php echo $value; ?>" aria-describedby="<?php echo $field; ?>Help" placeholder="<?php echo $placeholder; ?>"<?php if ($attributes) echo ' '.$attributes; ?>>
        </div>
        <?php
    }

    function email ($label, $field, $value, $placeholder, $attributes)
    {
        ?>
        <div class="form-group">
            <label for="<?php echo $field; ?>"><?php echo $label; ?><?php if ($attributes == 'required') echo ' *'; ?></label>
            <input type="email" class="form-control" name="<?php echo $field; ?>" id="<?php echo $field; ?>" aria-describedby="<?php echo $field; ?>Help" value="<?php echo $value; ?>" placeholder="<?php echo $placeholder; ?>"<?php if ($attributes) echo ' '.$attributes; ?>>
        </div>
        <?php
    }

    function number ($label, $field, $value, $placeholder, $min, $max, $step)
    {
        ?>
        <div class="form-group">
            <label for="<?php echo $field; ?>"><?php echo $label; ?></label>
            <input type="number" class="form-control" name="<?php echo $field; ?>" id="<?php echo $field; ?>" aria-describedby="<?php echo $field; ?>Help" value="<?php echo $value; ?>" placeholder="<?php echo $placeholder; ?>" min="<?php echo $min; ?>" max="<?php echo $max; ?>" step="<?php echo $step; ?>">
        </div>
        <?php
    }

    function password ($label, $field, $value, $placeholder, $attributes)
    {
        ?>
        <div class="form-group">
            <label for="<?php echo $field; ?>"><?php echo $label; ?><?php if ($attributes == 'required') echo ' *'; ?></label>
            <input type="password" class="form-control" name="<?php echo $field; ?>" id="<?php echo $field; ?>" aria-describedby="<?php echo $field; ?>Help" value="<?php echo $value; ?>" placeholder="<?php echo $placeholder; ?>"<?php if ($attributes) echo ' '.$attributes; ?>>
        </div>
        <?php
    }

    function radioToggle ($label, $field, $values, $table, $id, $checked, $class)
    {
        if (!$id):
            $checked = $checked;
        else:
            // Connect to DB
            $db = getDbInstance();

            // Query
            $db->where('id', $id);
            $checked = $db->getValue($table, $field);
        endif;

        $n = mt_rand(0, 99);
        $yesno = (!$checked) ? 'no' : 'yes';
        ?>
        <div class="form-group form-toggle<?php if ($class) echo ' '.$class; ?>">
            <div class="form-label"><?php echo $label; ?></div>
            <div id="cover<?php echo $n; ?>"<?php if ($class == 'yesno') echo ' class="'.$yesno.'"'; ?>>
                <?php foreach ($values as $key => $value): ?>
                <input type="radio" name="<?php echo $field; ?>" id="<?php echo $value.$n; ?>" value="<?php echo $key; ?>"<?php if ($key == $checked) echo ' checked="checked"'; ?> />
                <label for="<?php echo $value.$n; ?>" class="btn btn-sm"><?php echo ucfirst($value); ?></label>
                <?php endforeach; ?>
            </div>
        </div>
        <?php if ($class == 'yesno'): ?>
        <script>
        (function (){
            var radios = document.getElementsByName('<?php echo $field; ?>');
            console.log(radios);
            for (var i = 0; i < radios.length; i++)
            {
                radios[i].onclick = function()
                {
                    document.getElementById('cover<?php echo $n; ?>').className = this.id;
                }
            }
        })();
        </script>
        <?php endif; ?>
        <?php
    }

    function select ($label, $field, $values, $table, $id, $selected, $option, $multiple)
    {
        if (!$id):
            $selected = $selected;
        else:
            // Connect to DB
            $db = getDbInstance();

            // Query
            $db->where('id', $id);
            $selected = $db->getValue ($table, $field);
        endif;
        ?>
        <div class="form-group">
            <label for="<?php echo $field; ?>"><?php echo $label; ?></label>
            <select name="<?php echo $field; ?>" id="<?php echo $field; ?>" class="form-control"<?php if($multiple) echo ' multiple'; ?>>
                <?php echo $option; ?>
                <?php foreach($values as $key => $value): ?>
                    <option name="<?php echo lcfirst($value); ?>" value="<?php echo $key; ?>"<?php if ($key == $selected) echo ' selected'; ?>><?php echo $value; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php
    }

    function selectDB ($label, $field, $value, $name, $fromTable, $toTable, $toField, $order_by, $option, $required=0, $multiple=0)
    {
        // Connect to DB
        $db = getDbInstance();

        $db->where('published', 1);
        $db->orderBy($order_by, 'asc');
        $fromResult = $db->get($fromTable, null, 'id, '.$name);
        //echo '<pre>';print_r($fromResult);echo '</pre>'.'<br />';

        if($value):
            $db->where($toField, $value);
            $toResult = $db->getOne($toTable, $field);
            //echo '<pre>';print_r($toResult);echo '</pre>'.'<br />';
        endif;
        ?>
        <div class="form-group">
            <label for="<?php echo $field; ?>"><?php echo $label; ?><?php if ($required) echo ' *'; ?></label>
            <select id="<?php echo $field; ?>" class="form-control" name="<?php echo $field; ?>"<?php if ($required) echo ' required'; ?><?php if($multiple) echo ' multiple'; ?>>
                <?php echo $option; ?>
                <?php if (count($fromResult)): ?>
                <?php foreach ($fromResult as $row): ?>
                <option name="<?php echo lcfirst($row[$name]); ?>" value="<?php echo $row['id']; ?>"<?php if($value) echo $selected = ($toResult[$field] == $row['id']) ? ' selected="selected"' : ''; ?>><?php echo ucfirst($row[$name]); ?></option>
                <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <?php
    }

    function tel ($label, $field, $value, $placeholder, $attributes)
    {
        ?>
        <div class="form-group">
            <label for="<?php echo $field; ?>"><?php echo $label; ?><?php if ($attributes == 'required') echo ' *'; ?></label>
            <input type="tel" class="form-control" name="<?php echo $field; ?>" id="<?php echo $field; ?>" aria-describedby="<?php echo $field; ?>Help" value="<?php echo $value; ?>" placeholder="<?php echo $placeholder; ?>"<?php if ($attributes) echo ' '.$attributes; ?>>
        </div>
        <?php
    }

    function text ($label, $field, $value, $placeholder, $attributes)
    {
        ?>
        <div class="form-group">
            <label for="<?php echo $field; ?>"><?php echo $label; ?><?php if ($attributes == 'required') echo ' *'; ?></label>
            <input type="text" class="form-control" name="<?php echo $field; ?>" id="<?php echo $field; ?>" aria-describedby="<?php echo $field; ?>Help" value="<?php echo $value; ?>" placeholder="<?php echo $placeholder; ?>"<?php if ($attributes) echo ' '.$attributes; ?>>
        </div>
        <?php
    }

    function textarea ($label, $field, $value, $attributes)
    {
        ?>
        <label for="<?php echo $field; ?>"><?php echo $label; ?><?php if ($attributes == 'required') echo ' *'; ?></label>
        <textarea class="form-control" id="<?php echo $field; ?>" name="<?php echo $field; ?>" <?php if ($attributes) echo ' '.$attributes; ?>><?php echo $value; ?></textarea>
        <?php
    }

    function url ($label, $field, $placeholder, $attributes)
    {
        ?>
        <div class="form-group">
            <label for="<?php echo $field; ?>"><?php echo $label; ?><?php if ($attributes == 'required') echo ' *'; ?></label>
            <input type="url" class="form-control" name="<?php echo $field; ?>" id="<?php echo $field; ?>" aria-describedby="<?php echo $field; ?>Help" placeholder="<?php echo $placeholder; ?>"<?php if ($attributes) echo ' '.$attributes; ?>>
        </div>
        <?php
    }

     function file ($label, $field, $value, $placeholder, $attributes)
    {
        ?>
        <div class="form-group">
            <label for="<?php echo $field; ?>"><?php echo $label; ?><?php if ($attributes == 'required') echo ' *'; ?></label>
            <input type="file" class="form-control" name="file" id="<?php echo $field; ?>" aria-describedby="<?php echo $field; ?>Help" value="<?php echo $value; ?>" placeholder="<?php echo $placeholder; ?>"<?php if ($attributes) echo ' '.$attributes; ?>>
        </div>
        <?php
    }
}
?>