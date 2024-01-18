<?php 

class DropdownBuilder {
  private $name = '';
  private $title = '';
  private $values = [];
  private $keyName = '';
  private $valueName = '';
  private $infoKey = null;
  private $multiple = '';
  private $selected = '';
  private $selectedKey = '';


  public function __construct() {}

  public function clear() {
    $this->name = '';
    $this->title = '';
    $this->values = [];
    $this->keyName = '';
    $this->valueName = '';
    $this->infoKey = null;
    $this->multiple = '';
    $this->selected = '';
    $this->selectedKey = '';
    return $this;
  }

  public function setName($name) {
    $this->name = $name;
    return $this;
  }

  public function setTitle($title) {
    $this->title = $title;
    return $this;
  }

  public function setValues($values) {
    $this->values = $values;
    return $this;
  }

  public function setKeyName($keyName) {
    $this->keyName = $keyName;
    return $this;
  }

  public function setValueName($valueName) {
    $this->valueName = $valueName;
    return $this;
  }
  public function setInfoKey($infoKeyName) {
    $this->infoKey = $infoKeyName;
    return $this;
  }
  public function setMultiple() {
    $this->multiple = 'multiple';
    return $this;
  }
  public function setSelectedKey($key) {
    $this->selectedKey = $key;
    return $this;
  }

  public function render() {
    echo "
    <label class='white-text' for='".$this->name."'>".$this->title."</label><br>
    <select name='$this->name' class='dropdown-select' ".$this->multiple.">";
    foreach ($this->values as $key => $value) {
      $name =$this->infoKey !== null ? $value[$this->valueName].' ('.$value[$this->infoKey].' Ft)' : $value[$this->valueName];
      if($this->selectedKey == $key) $this->selected = 'selected'; else $this->selected = '';
      echo "<option class='dropdown-option' value='".$value[$this->keyName]."' ".$this->selected.">".$name."</option>";
    }
    echo '</select>';
  }
  public function renderByValues() {
    echo "
    <label class='white-text' for='".$this->name."'>".$this->title."</label><br>
    <select name='$this->name' class='dropdown-select' ".$this->multiple.">";
    foreach ($this->values as $key => $value) {
      if($this->selectedKey == $key) $this->selected = 'selected'; else $this->selected = '';
      echo "<option class='dropdown-option' value='".$key."' ".$this->selected.">".$value."</option>";
    }
    echo '</select>';
    }
}

?>