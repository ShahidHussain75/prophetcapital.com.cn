<?php
/**
 * Template name: User registration
 */

get_header(); ?>
<style>
 .transparent.navigation-header .featured > a
{
	color: #fff;
}
</style>


<div id="main-content" class="registration">
	<div class="vc_row-fluid window_height centered-content">
		<div class="container">
        

<div class="vc_col-sm-12">
<div class="reg-page-header">

			<div id="reg_box" class="col-sm-4 col-sm-offset-4">
            <h4 class="text-center"><?php if (ICL_LANGUAGE_CODE == 'en') {  ?>New user<?php } else { ?>新用户注册<?php } ?></h4>
            <div class="downloadtext">
            <?php if (ICL_LANGUAGE_CODE == 'en') {  ?>According to Article 92 of《The Law of the People's Republic of China on Securities Investment Fund》, private investment funds can only raise money from qualified investors, and are not permitted to be advertised or promoted to publicity through the press, radio, television, Internet or any other public communication. Therefore, the information of our funds is only available to our clients and potential qualified investors. 
Please download the document: <a href="<?php echo get_home_url(); ?>/wp-content/uploads/upme/document.docx?from=singlemessage&isappinstalled=0" target="blank">"registration application template "</a>, fill in your information and sign/stamp, and e-mail the scan file to prophet01@prophetcapital.cn. Your account will be restricted unless we receive and approve your application file.<?php } else { ?>根据《中华人民共和国证券投资基金法》相关规定：“第九十二条 非公开募集基金，不得向合格投资者之外的单位和个人募集资金，不得通过报刊、电台、电视台、互联网等公众传播媒体或者讲座、报告会、分析会等方式向不特定对象宣传推介。”因此，我公司所管理基金的详细信息仅供现有和潜在合格投资者浏览。
<br />
请将该文件下载：<a href="<?php echo get_home_url(); ?>/wp-content/uploads/upme/document.docx?from=singlemessage&isappinstalled=0" target="blank">《注册资料提交范本》</a>，并认证填写您的相关信息发送至客服邮箱prophet01@prophetcapital.cn经我公司人员人工审核后注册生效 <?php } ?>
。
</div>

               <?php echo do_shortcode('[upme_registration]'); ?>
               
               <div class="clearfix"></div>
			</div>

</div>
</div>
		</div>
	</div>
</div>

<?php wp_footer(); ?>
