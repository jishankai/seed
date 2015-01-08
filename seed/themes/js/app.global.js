function hoverClass(){
	addHover(".b_list_03 a");
	addHover(".b_con_11 .b_btn_07");
	addHover(".b_con_13 .squer");
	addHover(".b_btn_02");
	addHover(".b_btn_03");
	addHover(".b_btn_04");
	addHover(".b_btn_05");
	addHover(".b_btn_07");
	addHover(".b_btn_08");
	addHover(".b_ico");
	addHover(".next_page");

	addHover(".next_page_left");
	addHover(".b_fsalediv .btn");
	addHover(".b_btn53");
	addHover(".b_bg01 .b_icon01");
	addHover(".b_bg01 .b_icon01.b_icon01left");
	addHover(".a");
	addHover(".a_btn1");

	addHover(".a_btn03");
	addHover(".a_btn09");
	addHover(".a_btn13");
	addHover(".a_btn14");
	addHover(".a_btn18 a");
	
	addHover(".a_btn19_1");
	addHover(".a_btn19_2");
	addHover(".a_btn19_3");
	
	addHover(".a_btn19_4");
	addHover(".a_btn20");
	addHover(".a_btn21");
	addHover(".a_btn22");

	
	addHover(".a_btn23");
	addHover(".home_icon1");
	addHover(".help_icon");
	addHover(".menu_icon1");
	addHover(".a_bar2 a.hover");
	addHover(".new_btn");
	
	
	addHover(".a_list1 ul li h3 .num01");
	addHover(".a_list1 ul li h3 .num02");
	
	addHover(".a_list1 ul li h3 .num03");
	addHover(".a_list1 ul li h3 .num04");
	addHover(".a_list1 ul li h3 .num05");
	addHover(".a_list1 ul li h3 .num06");
	addHover(".a_list1 ul li h3 .num07");
	addHover(".a_list1 ul li h3 .num08");
	addHover(".a_list1 ul li h3 .num09");
	addHover(".a_list1 ul li h3 .num10");
	
	addHover(".a_btn11");
	addHover(".a_btn04");
	addHover(".a_btn05 a");
	addHover(".a_bar3 img");
	
	addHover(".a_button01");
	addHover(".b_con_10.a_con_02 .tx02 em");
	addHover(".b_btn_07.a_btn_blue");
	addHover(".a_bg_01 .b_icobg .b_ico");
	addHover(".a_con_f01 .a_abtn1");
	
	
	addHover(".bg_001 .this_nav li");

}

function addHover(element){
	$(element).bind("touchstart",function(){
		$(this).addClass("hover");
	});
	$(element).bind("touchend",function(){
		$(this).removeClass("hover");
	});
}