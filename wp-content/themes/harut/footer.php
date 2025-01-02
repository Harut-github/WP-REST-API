<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package harut
 */

?>


<?php wp_footer(); ?>

<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>


<script> 

wp.api.loadPromise.done(function () {
    console.log('WP API is ready');
});


//Получаем токен JWT
function getAuthToken(callback) {
  fetch('http://localhost/mywp/harut/wp-json/custom/v1/get-token', { // URL вашего серверного API
    method: 'GET',
    credentials: 'include' // Для передачи cookie, если нужно
  })
    .then(response => response.json())
    .then(data => {
    if (data.token) {
      callback(data.token);
    } else {
      console.error('Ошибка получения токена:', data);
    }
  })
    .catch(error => console.error('Ошибка:', error));
}

  
//Uplod IMG 
$('.postform__img').on('change', function(event) {
    const file = event.target.files[0]; // Получаем файл из input

    if (file) {
        const reader = new FileReader();

        // Когда файл будет прочитан
        reader.onload = function(e) {
            // e.target.result — это URL изображения (в формате base64)
            const imageUrl = e.target.result;
            
            // Отображаем изображение на странице (предварительный просмотр)
            $('#preview-image').attr('src', imageUrl);
        };

        // Читаем файл как DataURL (base64)
        reader.readAsDataURL(file);
    }
});  



  
//GET - Method  
wp.api.loadPromise.done(function () {
    const posts = new wp.api.collections.Posts();

    posts.fetch().done(function (data) {
        const postsContainer = document.querySelector('.posts');

        data.forEach(post => {
            const postItem = document.createElement('div');
            postItem.classList.add('posts__item');

            // ID записи
            const postId = document.createElement('i');
            postId.textContent = `ID: ${post.id}`;

            // Заголовок записи
            const postTitle = document.createElement('strong');
            postTitle.textContent = post.title.rendered;

            // Контент записи
            const postContent = document.createElement('span');
            postContent.innerHTML = post.content.rendered;

            // Добавляем изображение (если есть)
            const postImage = document.createElement('img');
            if (post.featured_media) {
                fetch(`http://localhost/mywp/harut/wp-json/wp/v2/media/${post.featured_media}`)
                    .then(response => response.json())
                    .then(media => {
                        postImage.src = media.source_url;
                        postImage.alt = media.alt_text || 'Изображение записи';
                        postItem.appendChild(postImage);
                    })
                    .catch(err => console.error('Ошибка загрузки изображения:', err));
            }

            // Собираем структуру
            postItem.appendChild(postId);
            postItem.appendChild(postTitle);
            postItem.appendChild(postContent);

            postsContainer.appendChild(postItem);
        });
    }).fail(function (error) {
        console.error('Ошибка:', error);
    });
});



  
//POST - Method 
jQuery(document).ready(function($) {
    $('.postform__btn').on('click', function() {
    
        const title = $('.postform__title').val();
        const content = $('.postform__content').val();
        const imageFile = document.querySelector('.postform__img').files[0];
        if (!imageFile) {
            console.error('Выберите изображение!');
            return;
        }
  
       getAuthToken(function(token) {
            function uploadImage(imageFile, callback) {
                const formData = new FormData();
                formData.append('file', imageFile);

                fetch('http://localhost/mywp/harut/wp-json/wp/v2/media', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,  
                    },
                    body: formData,
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.id) {
                            callback(data.id);
                        } else {
                            console.error('Ошибка загрузки изображения:', data);
                        }
                    })
                    .catch(error => console.error('Ошибка:', error));
            }
      
       //Добвалим дани
            wp.api.loadPromise.done(function () {
                uploadImage(imageFile, function (imageId) {
                    const newPost = new wp.api.models.Post({
                        title: title,
                        content: content,
                        status: 'publish',
                        featured_media: imageId,
                    });

                    newPost.save().done(function (response) {
                        console.log('Запись успешно создана с изображением:', response);
                    }).fail(function (error) {
                        console.error('Ошибка создания записи:', error);
                    });
                });
            });
       
        });
    });
});


  
  
//DELETE - Method 
jQuery(document).ready(function($) {
    $('.postform__btn-del').on('click', function() {
    
        const postId = $('.postform__id').val(); // Получаем ID записи из кнопки
       
        if (!postId) {
            console.error('ID записи не указан!');
            return;
        }
    
    
  getAuthToken(function(token) {
    // Отправляем DELETE-запрос
    fetch(`http://localhost/mywp/harut/wp-json/wp/v2/posts/${postId}`, {
        method: 'DELETE',
        headers: {
            'Authorization': `Bearer ${token}`
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Ответ сервера:', data);

        // Проверяем успешность удаления
        if (data.deleted || data.status === 'trash') {
            console.log(`Запись с ID ${postId} успешно удалена:`, data);
            $(`#post-${postId}`).remove(); // Удаление элемента из DOM
        } else {
            console.error('Ошибка удаления записи: сервер вернул неожиданные данные', data);
        }
    })
    .catch(error => console.error('Ошибка:', error));
});

       
    });
});
  
//PUT - Method  
jQuery(document).ready(function($) {
    $('.postform__btn-put').on('click', function() {
        const postId = $('.postform-put .postform__id').val(); // Получаем ID записи из кнопки
        const title = $('.postform-put .postform__title').val(); // Получаем новый заголовок
        const content = $('.postform-put .postform__content').val(); // Получаем новое содержание

        if (!postId) {
            console.error('ID записи не указан!');
            return;
        }
        
        // Проверяем, что заголовок и контент не пустые
        if (!title) {
            console.error('Заголовок и содержание не могут быть пустыми!');
            return;
        }
        
        getAuthToken(function(token) {
            // Отправляем PUT-запрос для обновления записи
            fetch(`http://localhost/mywp/harut/wp-json/wp/v2/posts/${postId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify({
                    title: title,     // Новый заголовок
                    content: content, // Новое содержание
                    status: 'publish'  // Можно использовать 'draft' для черновика
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Ошибка ответа сервера');
                }
                return response.json();
            })
            .then(data => {
                console.log('Запись успешно обновлена:', data);

                // Обновление DOM, если необходимо
                if (data && data.id === postId) {
                    console.log(`Запись с ID ${postId} успешно обновлена:`, data);
                    // Обновить элементы DOM, если нужно
                    $(`#post-${postId} .post-title`).text(data.title.rendered); // Обновление заголовка
                    $(`#post-${postId} .post-content`).html(data.content.rendered); // Обновление содержания
                }
            })
            .catch(error => console.error('Ошибка:', error));
        });
    });
});

  
  
  
//PATCH - Method    
jQuery(document).ready(function($) {
    $('.postform__btn-patch').on('click', function() {
        const postId = $('.postform-patch .postform__id').val(); // Получаем ID записи из кнопки
        const title = $('.postform-patch .postform__title').val(); // Новый заголовок (частичное обновление)
        const content = $('.postform-patch .postform__content').val(); // Новое содержание (частичное обновление)

        if (!postId) {
            console.error('ID записи не указан!');
            return;
        }

        // Проверяем, что хотя бы одно из полей не пустое для частичного обновления
        if (!title) {
            console.error('Нужно указать хотя бы одно поле для обновления!');
            return;
        }

        getAuthToken(function(token) {
            // Создаем объект для данных, которые будем обновлять (частично)
            const updateData = {};
            if (title) updateData.title = title; // Если заголовок не пустой, добавляем его
            if (content) updateData.content = content; // Если содержание не пустое, добавляем его

            // Отправляем PATCH-запрос для частичного обновления записи
            fetch(`http://localhost/mywp/harut/wp-json/wp/v2/posts/${postId}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify(updateData) // Отправляем только те данные, которые хотим обновить
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Ошибка ответа сервера');
                }
                return response.json();
            })
            .then(data => {
                console.log('Запись успешно частично обновлена:', data);

                // Обновление DOM, если необходимо
                if (data && data.id === postId) {
                    console.log(`Запись с ID ${postId} успешно обновлена:`, data);
                    // Обновить элементы DOM, если нужно
                    if (data.title.rendered) {
                        $(`#post-${postId} .post-title`).text(data.title.rendered); // Обновление заголовка
                    }
                    if (data.content.rendered) {
                        $(`#post-${postId} .post-content`).html(data.content.rendered); // Обновление содержания
                    }
                }
            })
            .catch(error => console.error('Ошибка:', error));
        });
    });
});

  
  
  
</script>
<script>

// ****************************WPDB GLOBAL******************************************//


var ajaxfilter = {
  ajaxurl: "<?php echo esc_url(admin_url('admin-ajax.php')); ?>"
};
  

 
//DELETE - WPDB 
jQuery(document).ready(function($) {
    $('.wpdb__btn-del').on('click', function() {
        const postId = $('.wpdb__id').val(); // Получаем ID записи из кнопки
  
    const filterData = {
            action: 'delete_posts', 
            id : postId,
        };  
    console.log(filterData.id);
     $.ajax({
            url: ajaxfilter.ajaxurl,
            type: 'POST',
            data: filterData,
            success: function(response) {
                alert('Post is Deleted');
            }
        });
            
    });
});
  
    

//CREATE TABLE - WPDB 
jQuery(document).ready(function($) {
    $('.wpdb__btn-create').on('click', function() {

        const  name = $('.wpdb__tablename').val(); // Получаем названя таблици 
  
        const filterData = {
            action: 'create_table', 
            name : name,
        };  

        $.ajax({
            url: ajaxfilter.ajaxurl,
            type: 'POST',
            data: filterData,
            success: function(response) {
                alert('New Table Created ');
            }
        });
            
    });
});


//Insert row this column - WPDB 
jQuery(document).ready(function($) {
    $('.wpdb__btn-insert').on('click', function() {

        const colum1 = $('.wpdb__colum-1').val(); // Получаем названя калонки 
        const colum2 = $('.wpdb__colum-2').val(); // Получаем названя калонки 
        
        const filterData = {
            action: 'insert_row', 
            colum1 : colum1,
            colum2 : colum2,
        };  

         $.ajax({
            url: ajaxfilter.ajaxurl,
            type: 'POST',
            data: filterData,
            success: function(response) {
                alert('New row is inserted in Table');
            }
        });
            
    });
});


</script>

</body>
</html>
