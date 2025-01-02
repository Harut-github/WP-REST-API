<?php /* Template Name: Home */ ?>
<?php get_header(); ?>


  <style>
    .postform-forms, .wpdb-forms{
      display:grid;
      grid-template-columns: 1fr 1fr 1fr 1fr;
      gap:30px;
    }
    .posts{
      display:flex;
      gap:30px;
    }
    .posts__item{
      max-width:200px;
      width:100%;
      border:solid 2px red;
      padding:15px;
      margin-top:30px;
      display:flex;
      flex-direction:column;
    }
    .posts__item img{
      max-width:50px;
    }
      
    .postform, .wpdb{
      border:solid 2px black; 
      padding:30px;
      border-radius:15px;
      display:flex;
      flex-direction:column;
      gap:15px;
      margin-top:45px;
    }
    .postform__title, .postform__id, .postform__content, .wpdb__id, .wpdb__tablename{
      border:solid 1px black;
    }
    .postform__content{
      border:solid 1px black;
    }
  </style>


  <div class="posts"></div>
  
  <div class="postform-forms">
    <div class="postform">
      <h1>POST - Method </h1>
      <input class="postform__title" type="text" />
      <textarea class="postform__content"></textarea>
      <input class="postform__img" type="file" />
      <img id="preview-image" src="" />
      <button class="postform__btn">Create</button>
    </div>

    <div class="postform">
      <h1>DELETE - Method </h1>
      <input class="postform__id" type="text" />
      <button class="postform__btn-del">Delete</button>
    </div>

    <div class="postform postform-put">
      <h1>PUT - Method </h1>
      <input class="postform__id" type="text" placeholder="*" />
      <input class="postform__title" type="text" placeholder="*" />
      <textarea class="postform__content"></textarea>
      <button class="postform__btn-put">Edite all</button>
    </div>
    
    <div class="postform postform-patch">
      <h1>Patch - Method </h1>
      <input class="postform__id" type="text" placeholder="*" />
      <input class="postform__title" type="text" placeholder="*" />
      <textarea class="postform__content"></textarea>
      <button class="postform__btn-patch">Edite</button>
    </div>
  </div>
  
  
  
  
  
  
  
  
  
<?php
// Подключение к глобальному объекту $wpdb
global $wpdb;

$table_name = $wpdb->prefix . 'bookings';

// SQL-запрос для получения всех заголовков записей
$results = $wpdb->get_results("
    SELECT *
    FROM {$table_name} 
");
  
// Проверяем, есть ли результаты
if (!empty($results)) {
    echo '<ul class="post-wbdb">'; // Начало списка

    // Цикл по результатам
    foreach ($results as $row) {
        echo '<li>' . esc_html($row->id) . ' ' . esc_html($row->column1) . ' ' . esc_html($row->column2) . '</li>'; 
    }

    echo '</ul>'; // Конец списка
} else {
    echo '<p>Записи не найдены.</p>';
}
?>
  
  
<?php
  
global $wpdb;
$table_name = $wpdb->prefix . 'bookings';
$results = $wpdb->get_var("
    SELECT id
    FROM {$table_name}  WHERE id = 5
");
  
var_dump($results);

?>

<div class="wpdb-forms">
  
  <div class="wpdb">
    <h1>DELETE - Method </h1>
    <input class="wpdb__id" type="text" placeholder="ID" />
    <button class="wpdb__btn-del">Delete</button>
  </div>

  <div class="wpdb">
    <h1>POST Create - Method - Table</h1>
    <input class="wpdb__tablename" type="text" placeholder="Table name" />
    <button class="wpdb__btn-create">Create Table</button>
  </div>

  <div class="wpdb">
    <h1>POST Inster - Method  - Row Colums</h1>
    <input class="wpdb__colum-1" type="text" placeholder="column 1" />
    <input class="wpdb__colum-2" type="text" placeholder="column 2" />
    <button class="wpdb__btn-insert">Instert row</button>
  </div>

</div>  
    


   

<?php get_footer(); ?>


