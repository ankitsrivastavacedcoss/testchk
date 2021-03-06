<?php
$resultCollection = $block->getConfigurableProductValue();
$getTrId = $block->getTrId();
$productId = $block->getProductId();
$attribute_labels = [];
?>
<div class='configurable-options-select'>		
	<?php $i=1; ksort($resultCollection); foreach($resultCollection as $attributes):?>
	    <div class="main-box">
	        <div class="input-box main-box<?= /* @noEscape */$getTrId;?> ">
	            	<input type ="hidden" 
	            	value="<?= /* @noEscape */$attributes['attribute_id'] ?>" 
	            	class="hidden-attribute-id" name="hidden-attribute-id"/> 
	                <select  name="<?= /* @noEscape */$attributes["label"]; ?>
	                [<?= /* @noEscape */$attributes['attribute_id'] ?>]" 
	                class="super-attribute-select select-attribute-box required" >
	                    <option value="" class="attribute-label"><?= /* @noEscape */$attributes["label"]; ?></option>
	                    <?php foreach($attributes["values"] as $values):?>
	                            <option 
	                            value="<?= /* @noEscape */$values['value_index']?>">
	                            <?= /* @noEscape */$values["label"]?>
	                            </option>
	                    <?php endforeach;?>
	                </select>
					<div class="config_hidden_val_by_attributes">
						<input type="hidden" 
								name="product[<?= /* @noEscape */$getTrId?>][config_hidden_val_by_attributes]" 
								class="config_hidden_val_by_attributes-<?= /* @noEscape */$attributes["label"] ?>" 
								value="">
					</div>
	                <?php 
	                	array_push($attribute_labels, $attributes["label"])
	                ?>
	        </div>
	    </div>
	<?php $i++; endforeach; ?> 
	<?php

		$productCollection = $block->getProductFactory();
		$stockItemRepositary = $block->getstockItemRepository();
		$product = $block->getProductRepository()->getById($productId);
		$config = $product->getTypeInstance(true);
		$childproduct = $config->getUsedProducts($product);
		$data = $product->getTypeInstance()->getConfigurableOptions($product);
		$final_array = [];
		ksort($data);
		foreach($data as $key => $attr){
		  	foreach($attr as $product){
		  			$pr = $block->getProductRepository()->get($product['sku']);
		  			$productId = $productCollection->getIdBySku($product['sku']);
		  			$productStock = $stockItemRepositary->get($productId);
		  			$productQty = $productStock->getQty();
		  			$final_array[$product['sku']][$key] = $product['value_index'];
		  			$final_array[$product['sku']]['price'] = $pr->getPrice();
		  			$final_array[$product['sku']]['qty']  = $productQty;
		  	}
		}
			$i = 0;
			$attributesCombinationArray = [];
			$priceArray = [];
			$qtyArray = [];

		foreach ($final_array as $key => $value) {
				$str = '';
				$str_key = '';
			foreach ($value as $key1 => $value1) {
				if ($key1 != 'price' && $key1 != 'qty') {
					$str .= $key1.'-'.$value1.',';
					$str_key .= $key1.',';
				}
			}
				$str = rtrim($str,',');
				$attributesCombinationArray[$i] = $str;
				$str_key = rtrim($str_key,',');
				

			foreach ($value as $key1 => $value1) {
				if ($key1 == 'price') {
					$price = '';
					$price .= $value1;
				}
			}
				$priceArray[$i] = $price;

			foreach ($value as $key1 => $value1) {
				if ($key1 == 'qty') {
					$qty = '';
					$qty .= $value1;
				}
			}
				$qtyArray[$i] = $qty;
				
			$i++;		
		}

		$config_hidden_array_price = json_encode($priceArray);
		$config_hidden_encoded_val = json_encode($attributesCombinationArray);
		$config_hidden_encoded_qty = json_encode($qtyArray);
	?>
		<input type="hidden" 
			   name="product[<?= /* @noEscape */$getTrId?>][config_hidden_array_price]" 
			   class="config_hidden_array_price" 
			   value='<?= /* @noEscape */$config_hidden_array_price?>'>
		<input type="hidden" 
			   name="product[<?= /* @noEscape */ $getTrId?>][config_hidden_key]" 
			   class="config_hidden_key" 
			   value="<?= /* @noEscape */ $str_key?>">
		<input type="hidden" 
			   name="product[<?= /* @noEscape */ $getTrId?>][config_hidden_val]" 
			   class="config_hidden_val" 
			   value="">
		<input type="hidden" 
			   name="product[<?= /* @noEscape */ $getTrId?>][config_hidden_encoded_val]" 
			   class="config_hidden_encoded_val" 
			   value='<?= /* @noEscape */ $config_hidden_encoded_val?>'>
		<input type="hidden" 
			   name="product[<?= /* @noEscape */ $getTrId?>][config_hidden_encoded_qty]" class="config_hidden_encoded_qty" 
			  value='<?= /* @noEscape */ $config_hidden_encoded_qty?>'>		
</div>

<div class="configurableAttributeProducts">
	<div class="attribute-div">
	<?php 
	foreach ($attribute_labels as $value) { ?>
		<span class="span-attribute span-attribute-class
		<?= /* @noEscape */ $getTrId?>-
		<?= /* @noEscape */ $value?>">
			<?= /* @noEscape */ $value;?>:
			<span class="span-attribute-val-class
			<?= /* @noEscape */ $getTrId?>-
			<?= /* @noEscape */ $value?>"></span>
		</span>
	<?php  }
	 ?>		
	</div>
</div>


