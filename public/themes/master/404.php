<?php 
/* Template Name: 404 */

get_header(); 

$lang = pll_current_language(); 

?>

<main class="flex items-center align-center justify-center flex-col">
    
    <?php if ($lang == 'ru') : ?>
        <h1>УПС! 404</h1>
        <p>Дорогой посетитель, вы попали на несуществующую страницу. Это значит, что контент, который вы искали, удален или никогда не был размещен по данному адресу</p>
        <p>Воспользуйтесь формой поиска, расположенной ниже, чтобы поискать на нашем сайте другие интересные вещи!</p>
    <?php else : ?>
        <h1>УПС! 404</h1>
        <p>Дорогий відвідувач, ви потрапили на неіснуючу сторінку. Це означає, що контент, який ви шукали, видалено або ніколи не було розміщено за цією адресою</p>
        <p>Скористайтеся формою пошуку, розташованою нижче, щоб знайти на нашому сайті інші цікаві речі!</p>
    <?php endif; ?>

</main>

<?php get_footer(); ?>