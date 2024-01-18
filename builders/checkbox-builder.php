<?php 

class CheckboxBuilder {
  private $values = [];
  private $checked = '';
  private $checkedKeys = [];

  public function __construct() {}
  public function clear() {
    $this->values = [];
    $this->checked = '';
    $this->checkedKeys = '';
    return $this;
  }
  public function setCheckedKeys($keys) {
    $this->checkedKeys = $keys;
    return $this;
  }
  public function setValues($values) {
    $this->values = $values;
    return $this;
  }
  public function render() {
    echo "<div class='checkbox-wrapper'>";
    foreach ($this->values as $key => $value) {
      if(in_array($key, $this->checkedKeys)) $this->checked = 'checked';
      else $this->checked = '';
      echo "
        <input type='checkbox' name='checkboxes[]' value='$key' ".$this->checked.">
        <label class='white-text' for='$key'>$value</label><br>
      ";
    }
    echo "</div>";
  }
}

?>