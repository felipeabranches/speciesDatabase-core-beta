 <?php 
class Pagination
{
    /*
     *
     */
    function pageNav ($pagination, $file_name, $page_name, $page)
    {
        if ($pagination > 1): ?> 
        <!-- Pagination -->
        <nav aria-label="<?php echo $page_name; ?> navigation">
            <?php
            if (!empty($_GET))
            {
                //we must unset $_GET[page] if previously built by http_build_query function
                unset($_GET['page']);
                //to keep the query string parameters intact while navigating to next/prev page,
                $http_query = "?".http_build_query($_GET);
            }
            else
            {
                $http_query = "?";
            }
            ?>
            <ul class="pagination">
                <li class="page-item<?php if ($page == 1) echo ' disabled'; ?>">
                    <a class="page-link" href="<?php echo $file_name; ?>.php<?php echo $http_query; ?>&page=<?php echo $page-1; ?>"<?php if ($page == 1) echo ' tabindex="-1" aria-disabled="true"'; ?> aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <?php for ($i = 1; $i <= $pagination; $i++): ?>
                <li class="page-item<?php if ($page == $i) echo ' active'; ?>">
                    <a class="page-link" href="<?php echo $file_name; ?>.php<?php echo $http_query; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>';
                <?php endfor; ?>
                <li class="page-item<?php if ($page == $pagination) echo ' disabled'; ?>">
                    <a class="page-link" href="<?php echo $file_name; ?>.php<?php echo $http_query; ?>&page=<?php echo $page+1; ?>"<?php if ($page == $pagination) echo ' tabindex="-1" aria-disabled="true"'; ?> aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
        <?php endif;
    }
}
