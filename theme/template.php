<?php
function theme_preprocess_page(&$variables) {   
  $status = drupal_get_http_header("status");  
  if($status == "404 Not Found") {      
    $variables['theme_hook_suggestions'][] = 'page__404';
  }
  
  if($status == "403 Forbidden") {      
    $variables['theme_hook_suggestions'][] = 'page__403';
  }

  if  (arg(0) == 'taxonomy' && arg(1) == 'term' && is_numeric(arg(2))) {
    unset($variables['page']['content']['system_main']['term_heading']['#prefix']);
    unset($variables['page']['content']['system_main']['term_heading']['#suffix']);
  }

  //if(isset($variables['node']) && $variables['node']->type == "your_content_type") {
    //drupal_set_title("my_title");   
  //}
  $variables['page']['titleclass'] = 'title__about';
  //dpm($variables);

  if (isset($variables['node'])){
    if (isset($variables['node']->field_descr)){
      $field_descr = field_get_items('node', $variables['node'], 'field_descr');
      $variables['page']['descr']  = field_view_value('node', $variables['node'], 'field_descr', $field_descr[0]);
    }
    if ($variables['node']->type == 'info') {
      $variables['page']['titleclass'] = 'title__info';
      $voc = taxonomy_vocabulary_load(5);
      if (isset($voc->field_descr)){
        $field_descr = field_get_items('taxonomy_vocabulary', $voc, 'field_descr');
        $variables['page']['descr']  = field_view_value('taxonomy_vocabulary', $voc, 'field_descr', $field_descr[0]);
      }
    }
  } else 
  if(arg(0) == 'taxonomy' && arg(1) == 'term' && is_numeric(arg(2))){
    $variables['page']['titleclass'] = 'title__tarifs';
  } else 
  if(function_exists('views_get_page_view') && views_get_page_view()){
    $view = views_get_page_view();
    //dpm($view);
    if ($view->name == 'tarifs') {
      $variables['page']['titleclass'] = 'title__tarifs';
      $voc = taxonomy_vocabulary_load(2);
      if (isset($voc->field_descr)){
        $field_descr = field_get_items('taxonomy_vocabulary', $voc, 'field_descr');
        $variables['page']['descr']  = field_view_value('taxonomy_vocabulary', $voc, 'field_descr', $field_descr[0]);
      }
    } else if ($view->name == 'info') {
      $variables['page']['titleclass'] = 'title__info';  
      $voc = taxonomy_vocabulary_load(5);
      if (isset($voc->field_descr)){
        $field_descr = field_get_items('taxonomy_vocabulary', $voc, 'field_descr');
        $variables['page']['descr']  = field_view_value('taxonomy_vocabulary', $voc, 'field_descr', $field_descr[0]);
      }
    } else if ($view->name == 'contacts') {
      $variables['page']['titleclass'] = 'title__contact';
      $voc = taxonomy_vocabulary_load(3);
      if (isset($voc->field_descr)){
        $field_descr = field_get_items('taxonomy_vocabulary', $voc, 'field_descr');
        $variables['page']['descr']  = field_view_value('taxonomy_vocabulary', $voc, 'field_descr', $field_descr[0]);
      }
    } else if ($view->name == 'price') {
      $variables['page']['titleclass'] = 'title__price';
      $voc = taxonomy_vocabulary_load(4);
      if (isset($voc->field_descr)){
        $field_descr = field_get_items('taxonomy_vocabulary', $voc, 'field_descr');
        $variables['page']['descr']  = field_view_value('taxonomy_vocabulary', $voc, 'field_descr', $field_descr[0]);
      }
    }
  }
}
function theme_breadcrumb($variables) {
 if (count($variables['breadcrumb']) > 1) {
   $lastitem = sizeof($variables['breadcrumb']);
   $title = drupal_get_title();
   $crumbs = '<div class="mybreadcrumb container">';
   $a=1;
   foreach($variables['breadcrumb'] as $value) {
       if ($a!=$lastitem){
        $crumbs .= str_replace('<a', '<a class="mybreadcrumb_link"', $value);
        $a++;
       }
       else {
           $crumbs .= '<span class="mybreadcrumb_item">'.$value.'</span>';
       }
   }
   $crumbs .= '</div>';
 return $crumbs;
 }
 else {
  return false;
 }
}
function theme_menu_tree__main_menu($variables) {
  if (preg_match("/\menutop_list__l2\b/i", $variables['tree'])){
    return '<ul class="menutop_list">' . $variables['tree'] . '</ul>';
  } else {
    return '<div class="menutop_wrap"><ul class="menutop_list__l2">' . $variables['tree'] . '</ul></div>';
  }
}
function theme_menu_link__main_menu(array $variables) {
  $element = $variables['element'];
  $sub_menu = '';
  if ($element['#below']) {
    foreach ($element['#below'] as $key => $val) {
      if (is_numeric($key)) {
        $element['#below'][$key]['#theme'] = 'menu_link__menu_products_inner';
      }
    }
    $sub_menu = drupal_render($element['#below']);
  }
  if (isset($element['#attributes']['class'][0]) and $element['#attributes']['class'][0] != 'active-trail') { unset($element['#attributes']['class'][0]); }
  if (isset($element['#attributes']['class'][1]) and $element['#attributes']['class'][1] != 'active-trail') { unset($element['#attributes']['class'][1]); }
  $element['#attributes']['class'][] = 'menutop_item';
  if ($variables['element']['#href'] == current_path() || ($variables['element']['#href'] == '<front>' && drupal_is_front_page())) {
    $element['#attributes']['class'][] = 'menutop_item__active';
    $output = l($element['#title'], $element['#href'], array('attributes' => array('class' => array('menutop_link', 'menutop_link__active'))));
  } else {
    $output = l($element['#title'], $element['#href'], array('attributes' => array('class' => array('menutop_link'))));
  } 
  if (isset($element['#attributes']['class'][1])){ 
    if ($element['#attributes']['class'][1] == 'active-trail') {
      unset($element['#attributes']['class'][1]);
      $output = l($element['#title'], $element['#href'], array('attributes' => array('class' => array('menutop_link', 'menutop_link__active'))));
    }
  }
  return '<li' . drupal_attributes($element['#attributes']) . '>' . $output . $sub_menu . "</li>\n";
}
function theme_menu_link__menu_products_inner($variables) {
  $element = $variables['element'];
  $sub_menu = '';
  if ($element['#below']) {
    $sub_menu = drupal_render($element['#below']);
  }
  unset($element['#attributes']['class'][0]);
  unset($element['#attributes']['class'][1]);
  unset($element['#attributes']['class'][2]);
  //$element['#attributes']['class'][] = 'menutop_item';
  $element['#attributes']['class'][] = 'menutop_item__l2';
  $output = l($element['#title'], $element['#href'], array('attributes' => array('class' => array('menutop_link__l2'))));
  return '<li' . drupal_attributes($element['#attributes']) . '>' . $output . $sub_menu . "</li>\n";
}
function theme_item_list($variables) {
  $items = $variables['items'];
  $title = $variables['title'];
  $type = $variables['type'];
  $attributes = $variables['attributes'];
  if(isset($variables['attributes']['class']['1']) and $variables['attributes']['class']['1'] == 'mypager_list'){
    $output = '<div class="mypager">';
  } else {
    $output = '<div class="item-list">';
  }
  if (isset($title) && $title !== '') {
    $output .= '<h3>' . $title . '</h3>';
  }
  if (!empty($items)) {
    $output .= "<$type" . drupal_attributes($attributes) . '>';
    $num_items = count($items);
    $i = 0;
    foreach ($items as $item) {
      $attributes = array();
      $children = array();
      $data = '';
      $i++;
      if (is_array($item)) {
        foreach ($item as $key => $value) {
          if ($key == 'data') {
            $data = $value;
          }
          elseif ($key == 'children') {
            $children = $value;
          }
          else {
            $attributes[$key] = $value;
          }
        }
      }
      else {
        $data = $item;
      }
      if (count($children) > 0) {
        $data .= theme_item_list(array('items' => $children, 'title' => NULL, 'type' => $type, 'attributes' => $attributes));
      }
      $output .= '<li' . drupal_attributes($attributes) . '>' . $data . "</li>\n";
    }
    $output .= "</$type>";
  }
  $output .= '</div>';
  return $output;
}
function theme_pager($variables) {
  $tags = $variables['tags'];
  $element = $variables['element'];
  $parameters = $variables['parameters'];
  $quantity = $variables['quantity'];
  global $pager_page_array, $pager_total;

  // Calculate various markers within this pager piece:
  // Middle is used to "center" pages around the current page.
  $pager_middle = ceil($quantity / 2);
  // current is the page we are currently paged to
  $pager_current = $pager_page_array[$element] + 1;
  // first is the first page listed by this pager piece (re quantity)
  $pager_first = $pager_current - $pager_middle + 1;
  // last is the last page listed by this pager piece (re quantity)
  $pager_last = $pager_current + $quantity - $pager_middle;
  // max is the maximum page number
  $pager_max = $pager_total[$element];
  // End of marker calculations.

  // Prepare for generation loop.
  $i = $pager_first;
  if ($pager_last > $pager_max) {
    // Adjust "center" if at end of query.
    $i = $i + ($pager_max - $pager_last);
    $pager_last = $pager_max;
  }
  if ($i <= 0) {
    // Adjust "center" if at start of query.
    $pager_last = $pager_last + (1 - $i);
    $i = 1;
  }
  // End of generation loop preparation.

  $li_first = theme('pager_first', array('text' => (isset($tags[0]) ? $tags[0] : t('« first')), 'element' => $element, 'parameters' => $parameters));
  $li_previous = theme('pager_previous', array('text' => (isset($tags[1]) ? $tags[1] : t('‹ previous')), 'element' => $element, 'interval' => 1, 'parameters' => $parameters));
  $li_next = theme('pager_next', array('text' => (isset($tags[3]) ? $tags[3] : t('next ›')), 'element' => $element, 'interval' => 1, 'parameters' => $parameters));
  $li_last = theme('pager_last', array('text' => (isset($tags[4]) ? $tags[4] : t('last »')), 'element' => $element, 'parameters' => $parameters));

  if ($pager_total[$element] > 1) {
    /*if ($li_first) {
      $items[] = array(
        'class' => array('mypager_item', 'pager-first'),
        'data' => $li_first,
      );
    }*/
    if ($li_previous) {
      $items[] = array(
        'class' => array('mypager_item', 'mypager-prev'),
        'data' => $li_previous,
      );
    }

    // When there is more than one page, create the pager list.
    if ($i != $pager_max) {
      if ($i > 1) {
        $items[] = array(
          'class' => array('mypager_item', 'pager-ellipsis'),
          'data' => '…',
        );
      }
      // Now generate the actual pager piece.
      for (; $i <= $pager_last && $i <= $pager_max; $i++) {
        if ($i < $pager_current) {
          $items[] = array(
            'class' => array('mypager_item'),
            'data' => theme('pager_previous', array('text' => $i, 'element' => $element, 'interval' => ($pager_current - $i), 'parameters' => $parameters)),
          );
        }
        if ($i == $pager_current) {
          $items[] = array(
            'class' => array('mypager_item', 'mypager_item__current'),
            'data' => $i,
          );
        }
        if ($i > $pager_current) {
          $items[] = array(
            'class' => array('mypager_item'),
            'data' => theme('pager_next', array('text' => $i, 'element' => $element, 'interval' => ($i - $pager_current), 'parameters' => $parameters)),
          );
        }
      }
      if ($i < $pager_max) {
        $items[] = array(
          'class' => array('mypager_item', 'pager-ellipsis'),
          'data' => '…',
        );
      }
    }
    // End generation.
    if ($li_next) {
      $items[] = array(
        'class' => array('mypager_item', 'mypager-next'),
        'data' => $li_next,
      );
    }
    /*if ($li_last) {
      $items[] = array(
        'class' => array('mypager_item', 'pager-last'),
        'data' => $li_last,
      );
    }*/
    return '<h2 class="element-invisible">' . t('Pages') . '</h2>' . theme('item_list', array(
      'items' => $items,
      'attributes' => array('class' => array('pager', 'mypager_list')),
    ));
  }
}














function rufsistems_preprocess_page(&$variables) {
  if (isset($variables['node']->type)) {
    $nodetype = $variables['node']->type;
    $variables['theme_hook_suggestions'][] = 'page__' . $nodetype;
  }
  if (arg(0) == 'print') {
    $variables['theme_hook_suggestions'][] = 'page__calc__print';

  }
}
/*function rufsistems_webform_display_file($variables) {
  $element = $variables['element'];

  $file = $element['#value'];
  //$url = !empty($file) ? webform_file_url($file->uri) : t('no upload');
  $url = !empty($file) ? webform_file_url($file->uri) : t('QQQQQQQQQQQQQQ');
  //return !empty($file) ? ($element['#format'] == 'text' ? $url : l($file->filename, $url)) : ' ';
  return 'qw';
}*/
function rufsistems_breadcrumb($variables) {
 if (count($variables['breadcrumb']) > 1) {
   $lastitem = sizeof($variables['breadcrumb']);
   $title = drupal_get_title();
   $crumbs = '<div class="mybreadcrumb">';
   $a=1;
   foreach($variables['breadcrumb'] as $value) {
       if ($a!=$lastitem){
        $crumbs .= str_replace('<a', '<a class="mybreadcrumb_link"', $value);
        $a++;
       }
       else {
           $crumbs .= '<span class="mybreadcrumb_item">'.$value.'</span>';
       }
   }
   $crumbs .= '</div>';
 return $crumbs;
 }
 else {
  return false;
 }
}
function rufsistems_menu_tree__menu_top($variables) {
  if (preg_match("/\menutop_list__l2\b/i", $variables['tree'])){
    return '<ul class="menutop_list">' . $variables['tree'] . '</ul>';
  } else {
    return '<ul class="menutop_list menutop_list__l2">' . $variables['tree'] . '</ul>';
  }
}
function rufsistems_menu_tree__menu_left($variables) {
  return '<ul class="lmenu_list">' . $variables['tree'] . '</ul>';
}
function rufsistems_menu_tree__menu_bottom($variables) {
  return '<ul class="menubottom_list">' . $variables['tree'] . '</ul>';
}
function rufsistems_menu_link__menu_top(array $variables) {
  $element = $variables['element'];
  $sub_menu = '';
  if ($element['#below']) {
    foreach ($element['#below'] as $key => $val) {
      if (is_numeric($key)) {
        $element['#below'][$key]['#theme'] = 'menu_link__menu_products_inner';
      }
    }
    $sub_menu = drupal_render($element['#below']);
  }
  if (isset($element['#attributes']['class'][0]) and $element['#attributes']['class'][0] != 'active-trail') { unset($element['#attributes']['class'][0]); }
  if (isset($element['#attributes']['class'][1]) and $element['#attributes']['class'][1] != 'active-trail') { unset($element['#attributes']['class'][1]); }
  $element['#attributes']['class'][] = 'menutop_item';
  if ($variables['element']['#href'] == current_path() || ($variables['element']['#href'] == '<front>' && drupal_is_front_page())) {
    $element['#attributes']['class'][] = 'menutop_item__active';
    $output = l($element['#title'], $element['#href'], array('attributes' => array('class' => array('menutop_link', 'menutop_link__active'))));
  } else {
    $output = l($element['#title'], $element['#href'], array('attributes' => array('class' => array('menutop_link'))));
  } 
  if (isset($element['#attributes']['class'][1])){ 
    if ($element['#attributes']['class'][1] == 'active-trail') {
      unset($element['#attributes']['class'][1]);
      $output = l($element['#title'], $element['#href'], array('attributes' => array('class' => array('menutop_link', 'menutop_link__active'))));
    }
  }
  return '<li' . drupal_attributes($element['#attributes']) . '>' . $output . $sub_menu . "</li>\n";
}
function rufsistems_menu_link__menu_products_inner($variables) {
  $element = $variables['element'];
  $sub_menu = '';
  if ($element['#below']) {
    $sub_menu = drupal_render($element['#below']);
  }
  unset($element['#attributes']['class'][0]);
  unset($element['#attributes']['class'][1]);
  unset($element['#attributes']['class'][2]);
  $element['#attributes']['class'][] = 'menutop_item';
  $element['#attributes']['class'][] = 'menutop_item__l2';
  $output = l($element['#title'], $element['#href'], array('attributes' => array('class' => array('menutop_link', 'menutop_link__l2'))));
  return '<li' . drupal_attributes($element['#attributes']) . '>' . $output . $sub_menu . "</li>\n";
}
function rufsistems_menu_link__menu_left(array $variables) {
  $element = $variables['element'];
  $sub_menu = '';

  if (isset($element['#attributes']['class'][0]) and $element['#attributes']['class'][0] != 'active-trail') { unset($element['#attributes']['class'][0]); }
  if (isset($element['#attributes']['class'][1]) and $element['#attributes']['class'][1] != 'active-trail') { unset($element['#attributes']['class'][1]); }
  $element['#attributes']['class'][] = 'lmenu_item';
  if ($variables['element']['#href'] == current_path() || ($variables['element']['#href'] == '<front>' && drupal_is_front_page())) {
    $element['#attributes']['class'][] = 'lmenu_item__active';
    $output = l($element['#title'], $element['#href'], array('attributes' => array('class' => array('lmenu_link', 'lmenu_link__active'))));
  } else {
    $output = l($element['#title'], $element['#href'], array('attributes' => array('class' => array('lmenu_link'))));
  } 
  if (isset($element['#attributes']['class'][1])){ 
    if ($element['#attributes']['class'][1] == 'active-trail') {
      unset($element['#attributes']['class'][1]);
      $output = l($element['#title'], $element['#href'], array('attributes' => array('class' => array('lmenu_link', 'lmenu_link__active'))));
    }
  }
  return '<li' . drupal_attributes($element['#attributes']) . '>' . $output . $sub_menu . "</li>\n";
}
function rufsistems_menu_link__menu_bottom(array $variables) {
  $element = $variables['element'];
  $sub_menu = '';

  if (isset($element['#attributes']['class'][0]) and $element['#attributes']['class'][0] != 'active-trail') { unset($element['#attributes']['class'][0]); }
  if (isset($element['#attributes']['class'][1]) and $element['#attributes']['class'][1] != 'active-trail') { unset($element['#attributes']['class'][1]); }
  $element['#attributes']['class'][] = 'menubottom_item';
  if ($variables['element']['#href'] == current_path() || ($variables['element']['#href'] == '<front>' && drupal_is_front_page())) {
    $element['#attributes']['class'][] = 'menubottom_item__active';
    $output = l($element['#title'], $element['#href'], array('attributes' => array('class' => array('menubottom_link', 'menubottom_link__active'))));
  } else {
    $output = l($element['#title'], $element['#href'], array('attributes' => array('class' => array('menubottom_link'))));
  } 
  if (isset($element['#attributes']['class'][1])){ 
    if ($element['#attributes']['class'][1] == 'active-trail') {
      unset($element['#attributes']['class'][1]);
      $output = l($element['#title'], $element['#href'], array('attributes' => array('class' => array('menubottom_link', 'menubottom_link__active'))));
    }
  }
  return '<li' . drupal_attributes($element['#attributes']) . '>' . $output . $sub_menu . "</li>\n";
}
function rufsistems_file_link($variables) {
  $file = $variables['file'];
  $icon_directory = $variables['icon_directory'];

  $url = file_create_url($file->uri);
  $icon = theme('file_icon', array('file' => $file, 'icon_directory' => $icon_directory));

  // Set options as per anchor format described at
  // http://microformats.org/wiki/file-format-examples
  $options = array(
    'attributes' => array(
      'type' => $file->filemime . '; length=' . $file->filesize,
    ),
  );

  // Use the description as the link text if available.
  if (empty($file->description)) {
    $link_text = $file->filename;
  }
  else {
    $link_text = $file->description;
    $options['attributes']['title'] = check_plain($file->filename);
  }

  //return '<span class="filed">' . $icon . ' ' . l($link_text, $url, $options) . '</span>';
  return l($link_text, $url, $options);
}
function rufsistems_item_list($variables) {
  $items = $variables['items'];
  $title = $variables['title'];
  $type = $variables['type'];
  $attributes = $variables['attributes'];
  if(isset($variables['attributes']['class']['1']) and $variables['attributes']['class']['1'] == 'mypager_list'){
    $output = '<div class="mypager">';
  } else {
    $output = '<div class="item-list">';
  }
  if (isset($title) && $title !== '') {
    $output .= '<h3>' . $title . '</h3>';
  }
  if (!empty($items)) {
    $output .= "<$type" . drupal_attributes($attributes) . '>';
    $num_items = count($items);
    $i = 0;
    foreach ($items as $item) {
      $attributes = array();
      $children = array();
      $data = '';
      $i++;
      if (is_array($item)) {
        foreach ($item as $key => $value) {
          if ($key == 'data') {
            $data = $value;
          }
          elseif ($key == 'children') {
            $children = $value;
          }
          else {
            $attributes[$key] = $value;
          }
        }
      }
      else {
        $data = $item;
      }
      if (count($children) > 0) {
        $data .= theme_item_list(array('items' => $children, 'title' => NULL, 'type' => $type, 'attributes' => $attributes));
      }
      $output .= '<li' . drupal_attributes($attributes) . '>' . $data . "</li>\n";
    }
    $output .= "</$type>";
  }
  $output .= '</div>';
  return $output;
}
function rufsistemsd_pager($variables) {
  $tags = $variables['tags'];
  $element = $variables['element'];
  $parameters = $variables['parameters'];
  $quantity = $variables['quantity'];
  global $pager_page_array, $pager_total;
  $pager_middle = ceil($quantity / 2);
  $pager_current = $pager_page_array[$element] + 1;
  $pager_first = $pager_current - $pager_middle + 1;
  $pager_last = $pager_current + $quantity - $pager_middle;
  $pager_max = $pager_total[$element];
  $i = $pager_first;
  if ($pager_last > $pager_max) {
    $i = $i + ($pager_max - $pager_last);
    $pager_last = $pager_max;
  }
  if ($i <= 0) {
    $pager_last = $pager_last + (1 - $i);
    $i = 1;
  }
  $li_first = theme('pager_first', array('text' => 'В начало', 'element' => $element, 'parameters' => $parameters));
  $li_previous = theme('mypager_previous', array('text' => (isset($tags[1]) ? $tags[1] : t('‹ previous')), 'element' => $element, 'interval' => 1, 'parameters' => $parameters));
  $li_next = theme('pager_next', array('text' => (isset($tags[3]) ? $tags[3] : t('next ›')), 'element' => $element, 'interval' => 1, 'parameters' => $parameters));
  $li_last = theme('pager_last', array('text' => 'В конец', 'element' => $element, 'parameters' => $parameters));
  if ($pager_total[$element] > 1) {
    if ($li_first) {
      $items[] = array(
        'class' => array('mypager_item', 'mypager_item__first'),
        'data' => $li_first,
      );
    }
    if ($li_previous) {
      $items[] = array(
        'class' => array('mypager_item', 'mypager-previous'),
        'data' => $li_previous,
      );
    }
    if ($i != $pager_max) {
      if ($i > 1) {
        $items[] = array(
          'class' => array('mypager_item', 'mypager-ellipsis'),
          'data' => '…',
        );
      }
      for (; $i <= $pager_last && $i <= $pager_max; $i++) {
        if ($i < $pager_current) {
          $items[] = array(
            'class' => array('mypager_item'),
            'data' => theme('pager_previous', array('text' => $i, 'element' => $element, 'interval' => ($pager_current - $i), 'parameters' => $parameters)),
          );
        }
        if ($i == $pager_current) {
          $items[] = array(
            'class' => array('mypager_item', 'mypager_item__current'),
            'data' => $i,
          );
        }
        if ($i > $pager_current) {
          $items[] = array(
            'class' => array('mypager_item'),
            'data' => theme('pager_next', array('text' => $i, 'element' => $element, 'interval' => ($i - $pager_current), 'parameters' => $parameters)),
          );
        }
      }
      if ($i < $pager_max) {
        $items[] = array(
          'class' => array('mypager_item', 'mypager-ellipsis'),
          'data' => '…',
        );
      }
    }
    if ($li_next) {
      $items[] = array(
        'class' => array('mypager_item', 'mypager-next'),
        'data' => $li_next,
      );
    }
    if ($li_last) {
      $items[] = array(
        'class' => array('mypager_item', 'mypager_item__last'),
        'data' => $li_last,
      );
    }
    return theme('item_list', array(
      'items' => $items,
      'attributes' => array('class' => array('pager', 'mypager_list')),
    ));
  }
}
function rufsistems_pager($variables) {
  $tags = $variables['tags'];
  $element = $variables['element'];
  $parameters = $variables['parameters'];
  $quantity = $variables['quantity'];
  global $pager_page_array, $pager_total;

  // Calculate various markers within this pager piece:
  // Middle is used to "center" pages around the current page.
  $pager_middle = ceil($quantity / 2);
  // current is the page we are currently paged to
  $pager_current = $pager_page_array[$element] + 1;
  // first is the first page listed by this pager piece (re quantity)
  $pager_first = $pager_current - $pager_middle + 1;
  // last is the last page listed by this pager piece (re quantity)
  $pager_last = $pager_current + $quantity - $pager_middle;
  // max is the maximum page number
  $pager_max = $pager_total[$element];
  // End of marker calculations.

  // Prepare for generation loop.
  $i = $pager_first;
  if ($pager_last > $pager_max) {
    // Adjust "center" if at end of query.
    $i = $i + ($pager_max - $pager_last);
    $pager_last = $pager_max;
  }
  if ($i <= 0) {
    // Adjust "center" if at start of query.
    $pager_last = $pager_last + (1 - $i);
    $i = 1;
  }
  // End of generation loop preparation.

  $li_first = theme('pager_first', array('text' => (isset($tags[0]) ? $tags[0] : t('« first')), 'element' => $element, 'parameters' => $parameters));
  $li_previous = theme('pager_previous', array('text' => (isset($tags[1]) ? $tags[1] : t('‹ previous')), 'element' => $element, 'interval' => 1, 'parameters' => $parameters));
  $li_next = theme('pager_next', array('text' => (isset($tags[3]) ? $tags[3] : t('next ›')), 'element' => $element, 'interval' => 1, 'parameters' => $parameters));
  $li_last = theme('pager_last', array('text' => (isset($tags[4]) ? $tags[4] : t('last »')), 'element' => $element, 'parameters' => $parameters));

  if ($pager_total[$element] > 1) {
    /*if ($li_first) {
      $items[] = array(
        'class' => array('mypager_item', 'pager-first'),
        'data' => $li_first,
      );
    }*/
    if ($li_previous) {
      $items[] = array(
        'class' => array('mypager_item', 'mypager-prev'),
        'data' => $li_previous,
      );
    }

    // When there is more than one page, create the pager list.
    if ($i != $pager_max) {
      if ($i > 1) {
        $items[] = array(
          'class' => array('mypager_item', 'pager-ellipsis'),
          'data' => '…',
        );
      }
      // Now generate the actual pager piece.
      for (; $i <= $pager_last && $i <= $pager_max; $i++) {
        if ($i < $pager_current) {
          $items[] = array(
            'class' => array('mypager_item'),
            'data' => theme('pager_previous', array('text' => $i, 'element' => $element, 'interval' => ($pager_current - $i), 'parameters' => $parameters)),
          );
        }
        if ($i == $pager_current) {
          $items[] = array(
            'class' => array('mypager_item', 'mypager_item__current'),
            'data' => $i,
          );
        }
        if ($i > $pager_current) {
          $items[] = array(
            'class' => array('mypager_item'),
            'data' => theme('pager_next', array('text' => $i, 'element' => $element, 'interval' => ($i - $pager_current), 'parameters' => $parameters)),
          );
        }
      }
      if ($i < $pager_max) {
        $items[] = array(
          'class' => array('mypager_item', 'pager-ellipsis'),
          'data' => '…',
        );
      }
    }
    // End generation.
    if ($li_next) {
      $items[] = array(
        'class' => array('mypager_item', 'mypager-next'),
        'data' => $li_next,
      );
    }
    /*if ($li_last) {
      $items[] = array(
        'class' => array('mypager_item', 'pager-last'),
        'data' => $li_last,
      );
    }*/
    return '<h2 class="element-invisible">' . t('Pages') . '</h2>' . theme('item_list', array(
      'items' => $items,
      'attributes' => array('class' => array('pager', 'mypager_list')),
    ));
  }
}