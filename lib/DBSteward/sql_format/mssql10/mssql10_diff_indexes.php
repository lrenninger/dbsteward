<?php
/**
 * Diffs indexes.
 *
 * @package DBSteward
 * @subpackage mssql10
 * @license http://www.opensource.org/licenses/bsd-license.php Simplified BSD License
 * @author Nicholas J Kiraly <kiraly.nicholas@gmail.com>
 */

class mssql10_diff_indexes extends pgsql8_diff_indexes {

  public static function diff_indexes_table($ofs, $old_schema, $old_table, $new_schema, $new_table) {
    // Drop indexes that do not exist in new schema or are modified
    foreach (self::get_drop_indexes($old_schema, $old_table, $new_schema, $new_table) as $index) {
      $ofs->write(mssql10_index::get_drop_sql($new_schema, $new_table, $index));
    }

    // Add new indexes
    if ($old_schema == NULL) {
      foreach (format_index::get_table_indexes($new_schema, $new_table) as $index) {
        $ofs->write(mssql10_index::get_creation_sql($new_schema, $new_table, $index) . "\n");
      }
    }
    else {
      foreach (self::get_new_indexes($old_schema, $old_table, $new_schema, $new_table) as $index) {
        $ofs->write(mssql10_index::get_creation_sql($new_schema, $new_table, $index) . "\n");
      }
    }
  }
}

?>
