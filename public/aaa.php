<?php 
$list = array(array('id'=>1, 'pid'=>0, 'deep'=>0, 'name'=>'测试1'), 
              array('id'=>2, 'pid'=>1, 'deep'=>1, 'name'=>'测试2'), 
              array('id'=>3, 'pid'=>0, 'deep'=>0, 'name'=>'测试3'), 
              array('id'=>4, 'pid'=>2, 'deep'=>2, 'name'=>'测试4'), 
              array('id'=>5, 'pid'=>2, 'deep'=>2, 'name'=>'测试5'), 
              array('id'=>6, 'pid'=>0, 'deep'=>0, 'name'=>'测试6'), 
              array('id'=>7, 'pid'=>2, 'deep'=>2, 'name'=>'测试7'), 
              array('id'=>8, 'pid'=>5, 'deep'=>3, 'name'=>'测试8'), 
              array('id'=>9, 'pid'=>3, 'deep'=>2, 'name'=>'测试9'), );

  function resolve($list) {
    $newList = $manages = $deeps = $inDeeps = array(); 
    foreach ($list as $row) { 
      $newList[$row['id']] = $row; 
    } 
    $list = null; 
    foreach ($newList as $row) { 
      if (! isset($manages[$row['pid']]) || ! isset($manages[$row['pid']]['children'][$row['id']])) { 
        if ($row['pid'] > 0 && ! isset($manages[$row['pid']]['children'])) $manages[$row['pid']] = $newList[$row['pid']]; $manages[$row['pid']]['children'][$row['id']] = $row; 
      } 
      if (! isset($inDeeps[$row['deep']]) || ! in_array($row['id'], $inDeeps[$row['deep']])) {
        $inDeeps[$row['deep']][] = array($row['pid'], $row['id']); 
      } 
    } 
    krsort($inDeeps); 
    array_shift($inDeeps); 
    foreach ($inDeeps as $deep => $ids) {
      foreach ($ids as $m) { 
              // 存在子栏目的进行转移
          if (isset($manages[$m[1]])) { $manages[$m[0]]['children'][$m[1]] = $manages[$m[1]]; $manages[$m[1]] = null; unset($manages[$m[1]]); } 
        } 
      } 
      return $manages[0]['children']; 
}


function resolve2($list, $pid = 0) {
  $manages = array(); 
    foreach ($list as $row) {
      if ($row['pid'] == $pid) {
      $manages[$row['id']] = $row; 
      $children = resolve2($list, $row['id']); 
      $children && $manages[$row['id']]['children'] = $children; 
    } 
  } 
  return $manages; 
}

  function resolve3($list,$pid = 1) {
    $newList = $manages = $deeps = $inDeeps = array(); 
    foreach ($list as $row) { 
      $newList[$row['id']] = $row; 
    } 
    
    $list = null; 
    foreach ($newList as $row) { 
      if (! isset($manages[$row['pid']]) || ! isset($manages[$row['pid']]['children'][$row['id']])) { 
        if ($row['pid'] > 0 && ! isset($manages[$row['pid']]['children'])) $manages[$row['pid']] = $newList[$row['pid']]; $manages[$row['pid']]['children'][$row['id']] = $row; 
      } 
      if (! isset($inDeeps[$row['deep']]) || ! in_array($row['id'], $inDeeps[$row['deep']])) {
        $inDeeps[$row['deep']][] = array($row['pid'], $row['id']); 
      } 
    } 
    krsort($inDeeps); 
    array_shift($inDeeps); 
    foreach ($inDeeps as $deep => $ids) {
      foreach ($ids as $m) { 
              // 存在子栏目的进行转移
          if (isset($manages[$m[1]])) { $manages[$m[0]]['children'][$m[1]] = $manages[$m[1]]; $manages[$m[1]] = null; unset($manages[$m[1]]); } 
        } 
      } 
      return $manages[0]['children']; 
}

echo '<pre>';
var_dump(resolve2($list,1));die;
