<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link  http://amner.me
 * @since 1.0.0
 *
 * @package    Jerc
 * @subpackage Jerc/admin/partials
 */

?>

<div class="wrap">
    <h1>Javascript Error Reporting</h1>
    <form>
        <input type='hidden' name='page' value='<?php echo $this->name; ?>'>
        <div class="tablenav top">

            <div class="alignleft">
                <label for="date-from-select" class="">Between:</label>
                <input type='datetime-local' name="time_from" id="date-from-select" value='<?php echo isset($_REQUEST['time_from']) ? $_REQUEST['time_from'] : ''; ?>'></input>
                <label for="date-to-select" class=""> : </label>
                <input type='datetime-local' name="time_to" id="date-to-select" value='<?php echo isset($_REQUEST['time_to']) ? $_REQUEST['time_to'] : ''; ?>'></input>
                <input type="submit" id="doaction" class="button action" value="Filter">
                
                
                <?php foreach ($this->getFilters() as $key => $value) : ?>
                    <?php if ($key !== 'time_from' && $key !== 'time_to') : ?>
                        <input type='hidden' name='<?php echo $key; ?>' value='<?php echo $value; ?>'>
                        <a class='button' href='<?php echo $this->removeFilterUrl($key); ?>'>
                            <span class="dashicons dashicons-remove" style='line-height:1.4;'></span>
                            <?php 
                            if ($key === 'userId') {
                                if ($value == 0) {
                                    echo "Anonymous";
                                } else {
                                    echo get_userdata($value)->user_nicename;
                                }
                            } else {
                                echo $value;
                            }
                            ?>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
                <a class="button" href="?page=<?php echo $this->name; ?>">Reset</a>
            </div>
            <?php $this->displayPagination(); ?>
            <br class="clear">
        </div>

        <table class="wp-list-table widefat fixed striped table-view-list pages" role="presentation">
            <thead>
                <tr>
                    <th>Time</td>
                    <th>Error</td>
                    <th>Script Name</th>
                    <th>User</td>
                    <th>IP Address</td>
                    <th>URL</th>
                    <th>UserAgent</th>
                </tr>
            </thead>
            <tbody>
                
                <?php foreach ($this->getData() as $row) : ?>
                    <tr>
                        <?php 
                        if (intval($row->userId) > 0) { 
                            $user = get_userdata($row->userId);
                        } ?>
                        <td>
                            <?php echo $row->timestamp; ?>
                        </td>
                        <td>
                            <a href='<?php echo $this->getFilterUrl('message', $row->message); ?>'>
                                <?php echo $row->message; ?>
                            </a>
                        </td>
                        <td>
                            <a href='<?php echo $this->getFilterUrl('script', $row->script); ?>'>
                                <?php echo $row->script; ?>
                            </a>
                        </td>
                        <td>
                            <a href='<?php echo $this->getFilterUrl('userId', (isset($user) ? $user->ID : 0)); ?>'>
                                <?php echo (isset($user) ? $user->user_nicename : 'Anonymous'); ?>
                            </a>
                        </td>
                        <td>
                            <a href='<?php echo $this->getFilterUrl('userIp', $row->userIp); ?>'>
                                <?php echo $row->userIp; ?>
                            </a>
                        </td>
                        <td>
                            <a href='<?php echo $this->getFilterUrl('pageUrl', $row->pageUrl); ?>'>
                                <?php echo $row->pageUrl; ?>
                            </a>
                        </td>
                        <td>
                            <a href='<?php echo $this->getFilterUrl('agent', $row->agent); ?>'>
                                <?php echo $row->agent; ?>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </form>
    <div class='tablenav bottom'>
        <div class='alignleft'>
            <form method='POST' action='<?php echo admin_url('admin-post.php'); ?>'>
                <?php wp_nonce_field($this->action); ?>
                <?php foreach ($this->getFilters() as $key => $value) : ?>
                    <input type='hidden' name='<?php echo $key; ?>' value='<?php echo $value; ?>'>
                <?php endforeach; ?>
                <button class='button' type='submit' name='action' value='<?php echo $this->action; ?>'>Export CSV</button>
            </form>
        </div>
        <?php $this->displayPagination(); ?>
    </div>
</div>