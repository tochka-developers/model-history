# History for Laravel Models

Easily add keeping track of model modifications.

## Installation

1. Add `"tochka-developers/model-history":"^0.1"` to the `require` section 
   of your `composer.json`

2. Publish package assets:
```shell
php artisan vendor:publish 
``` 
3. You may now edit the config file `model-history.php` to specify 
   the name of the table to store history records. Please do it _before_ 
   running the migrations. Default name is `history` which is quite reasonable.

4. Use `\Tochka\ModelHistory\HasHistory` trait in your model.

## History structure

History records for all tracked models are stored in the same table 
specified in the config file (`history` is the default name).
Each record contains the following data:
 - `changed_at` - time of modification;
 - `entity_name` - name of the table containing tracked model records;
 - `entity_id` - ID of the row in the tracked table the history entry relates to;
 - `action` - type of modification. The possible values are `create`, `update`, 
   `delete` and `restore`.
 - `new_data` - a JSON containing _new_ values. Therefore each history record is 
   essentially a diff to the previous version of the model.

## Warning

The history table always grows and is NEVER CLEANED UP by this package.
Please consider the possibility of the history table becoming the largest
in your database and occupying more space than all other tables
ultimately exhausting all available disk space.
