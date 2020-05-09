<?php if (!empty($products)): ?>
    <div class="product-one">
        <?php foreach ($products as $product): ?>
            <div class="col-md-4 product-left p-left">
                <div class="product-main simpleCart_shelfItem">
                    <a href="product/<?=$product->alias;?>" class="mask"><img class="img-responsive zoom-img" src="images/<?=$product->img;?>" alt="<?=$product->title;?>" /></a>
                    <div class="product-bottom">
                        <h3><?=$product->title;?></h3>
                        <p>Explore Now</p>
                        <h4>
                            <a class="add-to-cart-link js-add-to-cart-link" data-id="<?=$product->id;?>" href="cart/add?id=<?=$product->id;?>">
                                <i></i>
                            </a>
                            <span class=" item_price"><?=$currency['symbol_left'];?><?=$product->price * $currency['value'];?><?=$currency['symbol_right'];?>
                                <?php if ($product->old_price): ?>
                                    <small style="text-decoration: line-through;"><?=$currency['symbol_left'];?><?=$product->old_price * $currency['value'];?><?=$currency['symbol_right'];?></small>
                                <?php endif; ?>
                        </span>
                        </h4>
                    </div>
                    <?php if ($product->old_price): ?>
                        <div class="srch srch1">
                            <span>-<?php echo abs(ceil((($product->price * $currency['value']) / ($product->old_price * $currency['value'])) * 100) - 100); ?>%</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
        <div class="clearfix"></div>
        <div class="text-center">
            <p class="pagination-text"><?=count($products);?> товар(ов) из <?=$total;?></p>
            <?php if ($pagination->countPages > 1): ?>
                <?=$pagination;?>
            <?php endif; ?>
        </div>
    </div>
<?php else: ?>
    <h3>Товаров не найдено</h3>
<?php endif; ?>