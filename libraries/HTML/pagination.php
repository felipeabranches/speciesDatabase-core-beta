 <?php 
class Pagination
{
    function simplePagination ($pagination,$path_file,$page_title,$page)
    {

     if ($pagination > 1):
     ?> 
                    <!-- Pagination -->
                    <nav aria-label="<?php echo $page_title?> navigation">
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
                                <?php if($page!=1):?>
                                <a href="<?php echo $path_file; ?>.php<?php echo $http_query; ?>&page=<?php echo $page-1; ?>" class="page-link" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                                <?php endif;?>
                            <?php
                            for ($i = 1; $i <= $pagination; $i++)
                            {
                                ($page == $i) ? $li_class = ' active' : $li_class = "";
                                echo '<li class="page-item'.$li_class.'"><a href="'.$path_file.'.php'.$http_query.'&page='.$i.'" class="page-link">'.$i.'</a></li>';
                            }
                            ?>
                                <?php if($page!=$pagination):?>
                                
                                <a href="<?php echo $path_file; ?>.php<?php echo $http_query; ?>&page=<?php echo $page+1; ?>" class="page-link" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            <?php endif;?>
                            
                        </ul>
                    </nav>
                    <?php endif;
    }
}