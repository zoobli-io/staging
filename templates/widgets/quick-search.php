<div class="ts-form quick-search">
	<div class="ts-form-group ts-popup-component quick-search-keyword">
		<div class="ts-filter" readonly=""  ref="target">
			<i aria-hidden="true" class="las la-search"></i>
			<div class="ts-filter-text">Quick search</div><span class="ts-shortcut">CTRL+K</span>
		</div>
		<popup v-cloak  wrapper="ts-quicksearch-popup">
			<div class="ts-form-group">
			   <div class="ts-input-icon flexify">
			   <i aria-hidden="true" class="las la-search"></i>
			   <input type="text" placeholder="Quick search" class="autofocus"></div>
			</div>
			<div class="ts-form-group">
				<ul class="flexify simplify-ul quick-cpt-select ">
					<li class="ts-active"><a href="#">Places</a></li>
					<li><a href="#">People</a></li>
					<li><a href="#">Events</a></li>
					<li><a href="#">Jobs</a></li>
				</ul>
			</div>
			<div class="ts-term-dropdown ts-multilevel-dropdown">
			   <ul class="simplify-ul ts-form-group ts-term-dropdown-list min-scroll quick-search-list">
			   	    <li>
			   	      <a href="#" class="flexify">
		   	        	<img src="https://cdn.dribbble.com/users/4814079/avatars/mini/c61d5b65b2299f654096470bfbd282b4.png?1598230443">
			   	        <p>Maze Cafe<small>Place</small></p>
			   	       	
			   	       </a>
			        </li>
			   	    <li>
			   	     <a href="#" class="flexify">
			   	     <img src="https://cdn.dribbble.com/users/25514/avatars/mini/070810be04e642201206c8fbdffcbf8a.png?1455536235">
			   	        <p>Rammotion<small>Place</small></p>
			   	       
			   	     </a>
			   	    </li>
			   	    <li>
			         <a href="#" class="flexify">
			          <img src="https://cdn.dribbble.com/users/702789/avatars/mini/e66ce3992038d4efadb8c329f25aea78.png?1508747241">
			            <p>Sach pizza<small>Place</small></p>
			          
			         </a>
			        </li>
			        <li>
			         <a href="#" class="flexify">
			            <i aria-hidden="true" class="lar la-file-video"></i>
			            <p>Cafe<small>Places · Category</small></p>
			           
			         </a>
			      </li>
			      <li>
			         <a href="#" class="flexify">
			            <i aria-hidden="true" class="las la-wine-glass-alt"></i>
			            <p>Restaurant<small>Places · Category</small></p>
			           
			         </a>
			      </li>
			   </ul>
			</div>
			<template #footer>
				<div class="ts-popup-controller">
				   <ul class="flexify simplify-ul">
				      <li class="flexify"><a href="#" class="ts-btn ts-btn-1"><i class="las la-times" aria-hidden="true"></i>Close</a></li>
				      <li class="flexify"><a href="#" class="ts-btn ts-btn-2"><i aria-hidden="true" class="las la-search"></i>Show all</a></li>
				   </ul>
				</div>
			</template>
		</popup>
	</div>
</div>