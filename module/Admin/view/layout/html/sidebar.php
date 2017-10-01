<aside class="main-sidebar">
  	<section class="sidebar">
  		<ul class="sidebar-menu tree" data-widget="tree">
  			<li class="active">
		        <a href="/admin">
		          	<i class="fa fa-link"></i>
		          	<span>Dash Board</span>
		        </a>
	      	</li>

	   		<li >
		        <a href="/admin/chart">
		          	<i class="fa fa-bar-chart"></i>
		          	<span>Chart</span>
		        </a>
	      	</li>

	      	<li class="treeview">
		        <a href="">
		            <i class="glyphicon glyphicon-user"></i>
		            <span>Users</span>
		        </a>
		        <ul class="treeview-menu">
		            <li>
        	            <a href="<?= $this->url('users', ['action' => 'list']); ?>">
        	                <i class="glyphicon glyphicon-list"></i>
        	                <span>List User</span>
        	            </a>
		            </li>
		        </ul>
	      	</li>

	      	<li class="treeview">
		        <a href="">
		            <i class="glyphicon glyphicon-th-large"></i>
		            <span>Categories</span>
		        </a>
		        <ul class="treeview-menu">
		        	<li>
			            <a href="<?= $this->url('categories', ['action' => 'index']); ?>">
				            <i class="glyphicon glyphicon-list"></i>
				            <span>List Categories</span>
			            </a>
		            </li>
		          <li>
		            <a href="<?= $this->url('categories', ['action' => 'add']); ?>">
		                <i class="glyphicon glyphicon-plus"></i>
		                <span>Add Category</span>
		            </a>
		          </li>
		        </ul>
	      	</li>

	      	<li class="treeview">
		        <a href="">
		            <i class="glyphicon glyphicon-tag"></i>
		            <span>Products</span>
		        </a>
		        <ul class="treeview-menu">
		        	<li>
		            <a href="<?= $this->url('products', ['action' => 'list']); ?>">
		                <i class="glyphicon glyphicon-list"></i>
		                <span>List Product</span>
		            </a>
		            </li>
		            <li>
		                <a href="<?= $this->url('products', ['action' => 'add']); ?>">
		                	<i class="glyphicon glyphicon-plus"></i>
		                	<span>Add Product</span>
		                </a>
		            </li>
		        </ul>
	      	</li>

	      	<li class="treeview">
		        <a href="">
		            <i class="glyphicon glyphicon-home"></i>
		            <span>Stores</span>
		        </a>
		        <ul class="treeview-menu">
		        	<li>
		            <a href="<?= $this->url('stores', ['action' => 'list']); ?>">
		                <i class="glyphicon glyphicon-list"></i>
		                <span>List Store</span>
		            </a>
		            </li>
		            <li>
		                <a href="<?= $this->url('stores', ['action' => 'add']); ?>">
		                	<i class="glyphicon glyphicon-plus"></i>
		                	<span>Add Store</span>
		                </a>
		            </li>
		        </ul>
	      	</li>

	      	<li class="treeview">
                <a href="">
                    <i class="fa fa-truck"></i>
                    <span>Orders</span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="<?= $this->url('orders', ['action' => 'index']); ?>">
                            <i class="glyphicon glyphicon-list"></i>
                            <span>List Orders</span>
                        </a>
                    </li>
                    <li>
                        <a href="">
                            <i class="fa fa-calendar"></i>
                            <span>Order Plans</span>
                        </a>
                    </li>
                </ul>
          	</li>
          	<li class="treeview">
                <a href="">
                    <i class="fa fa-percent"></i>
                    <span>Sale Programs</span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="<?= $this->url('sale_programs', ['action' => 'index']); ?>">
                            <i class="glyphicon glyphicon-list"></i>
                            <span>List Sale Programs</span>
                        </a>
                    </li>
                    <li>
                        <a href="">
                            <i class="fa fa-calendar"></i>
                            <span>Sale Program Plans</span>
                        </a>
                    </li>
                </ul>
            </li>

        </ul>
    </section>
</aside>

