<h1><?php the_title(); ?></h1>

<div class="custom-fields">
    <?php 
        $employeeMetas = get_post_meta(get_the_ID(), 'employee');
        if (isset($employeeMetas[0])):
    ?>
        <h3 class="label">Description</h3>
        <p class="text"><?php echo $employeeMetas[0]; ?></p>
    <?php endif; ?>
    
    <?php
        $roleList = get_the_terms(get_the_ID(), 'role');
        if (!empty($roleList)):
            $roleString = join(', ', wp_list_pluck($roleList, 'name'));
    ?>
        <h3 class="label">Roles</h3>
        <p class="text"><?php echo $roleString; ?></p>
    <?php endif; ?>
</div>

<?php _s_post_thumbnail(); ?>

<div class="content">
    <?php the_content(); ?>
</div>