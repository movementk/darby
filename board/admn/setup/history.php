<?
include_once $_SERVER['DOCUMENT_ROOT']."/board/admn/include/head.php";
include $dir.$configDir."/admin_check.php";

$t100 = "top_mon";
$t108 = "navi_mon";
$left = "l1";

include_once $dir."/admn/include/admin_top.php";
include_once $dir."/admn/include/admin_left.php";

$searchVal = "page=".$page;
$PageBlock = 10;
$board_list_num = 100;
$board_tit_len = 75;

$mode = $site_prefix."history";

if(!isset($hcat)) $hcat = "경기도광주신도약기";

$TotalSQL = " select * from ".$mode." where hcat = '".$hcat."' $sql_common order by hyear asc, hmonth asc, hday asc, idx asc  ";
$TotalResult = sql_query($TotalSQL);
$TotalCount = @mysql_num_rows($TotalResult);

$total_page = ceil($TotalCount / $board_list_num);
if(!$page) $page = 1;
$from_record = ($page - 1) * $board_list_num;

$sql = $TotalSQL." limit $from_record, $board_list_num ";
$result = sql_query($sql);
$Count = @mysql_num_rows($result);

$write_pages = get_paging_admin($PageBlock, $page, $total_page, $_SERVER['PHP_SELF']."?page=");

if(empty($workType)){
	$workType = "HI";
} else {
	$asql = " select * from ".$mode." where idx= '".$idx."' ";
	$arow = sql_fetch($asql);
	$arow["files"] = get_file($site_prefix."history",$arow["idx"]);
}
?>
<div id="container">
	<div class="content_view">
		<div class="con_title">연혁 관리</div>
		<form name="info_form" method="post" action="/board/admn/_proc/setup/_banner_proc.php" enctype="MULTIPART/FORM-DATA">
		<input type="hidden" name="workType" value="<?=$workType?>">
		<input type="hidden" name="idx" value="<?=$arow["idx"]?>">
		<input type="hidden" name="URI" value="<?=$_SERVER['PHP_SELF']?>">
		<input type="hidden" name="mode" value="<?=$mode?>">
		<table class="write_table">
			<colgroup>
				<col style="width:120px;"></col>
				<col></col>
				<col style="width:120px;"></col>
				<col></col>
			</colgroup>
			<tbody>
			<tr>
				<th><label>분류</label></th>
				<td>
					<select name="hcat" exp title="분류" class="input">
						<option value="경기도광주신도약기" <?=$arow["hcat"]=="경기도광주신도약기"?"selected":""?>>경기도광주신도약기(2012~<?=date("Y",time())?>)</option>
						<option value="경기도광주성숙기" <?=$arow["hcat"]=="경기도광주성숙기"?"selected":""?>>경기도광주성숙기(1993~2011)</option>
						<option value="봉천동성장기" <?=$arow["hcat"]=="봉천동성장기"?"selected":""?>>봉천동성장기(1972~1993)</option>
						<option value="신대방동도약기" <?=$arow["hcat"]=="신대방동도약기"?"selected":""?>>신대방동도약기(1957~1972)</option>
						<option value="용문동설립기" <?=$arow["hcat"]=="용문동설립기"?"selected":""?>>용문동설립기(1952~1957)</option>
					</select>&nbsp;
				</td>
				<th><label>연/월/일</label></th>
				<td>
					<input type="text" class="input wd70" name="hyear" exp title="연" value="<?=$arow["hyear"]?>">&nbsp;
					<select name="hmonth" exp title="월" class="input">
						<option value="">선택</option>
						<option value="01" <?=$arow["hmonth"]==1?"selected":""?>>1월</option>
						<option value="02" <?=$arow["hmonth"]==2?"selected":""?>>2월</option>
						<option value="03" <?=$arow["hmonth"]==3?"selected":""?>>3월</option>
						<option value="04" <?=$arow["hmonth"]==4?"selected":""?>>4월</option>
						<option value="05" <?=$arow["hmonth"]==5?"selected":""?>>5월</option>
						<option value="06" <?=$arow["hmonth"]==6?"selected":""?>>6월</option>
						<option value="07" <?=$arow["hmonth"]==7?"selected":""?>>7월</option>
						<option value="08" <?=$arow["hmonth"]==8?"selected":""?>>8월</option>
						<option value="09" <?=$arow["hmonth"]==9?"selected":""?>>9월</option>
						<option value="10" <?=$arow["hmonth"]==10?"selected":""?>>10월</option>
						<option value="11" <?=$arow["hmonth"]==11?"selected":""?>>11월</option>
						<option value="12" <?=$arow["hmonth"]==12?"selected":""?>>12월</option>
					</select>&nbsp;
					<select name="hday" class="input">
						<option value="">선택</option>
						<?
						for($i=1;$i<=31;$i++){
							if($i < 10) $hday = "0".$i;
							else $hday = $i;
						?>
						<option value="<?=$hday?>" <?=$arow["hday"]==$hday?"selected":""?>><?=$i?>일</option>
						<?
						}
						?>
					</select>&nbsp;
				</td>
				<th><label>내용</label></th>
				<td><input type="text" class="input wd600" name="htext" exp title="설명" value="<?=$arow["htext"]?>"></td>
			</tr>
			</tbody>
		</table>
		<div class="btn_group">
			<button type="button" class="btn_a_b" onclick="form_ck();"><?=$workType=="HI"?"등 록":"수 정"?></button>
		</div>
		</form>
		<?=$hcat=="용문동설립기"?"<b>":""?><a href="<?=$_SERVER["PHP_SELF"]?>?hcat=<?=urlencode("용문동설립기")?>">[용문동설립기(1952~1957)]</a></b>&nbsp;&nbsp;
		<?=$hcat=="신대방동도약기"?"<b>":""?><a href="<?=$_SERVER["PHP_SELF"]?>?hcat=<?=urlencode("신대방동도약기")?>">[신대방동도약기(1957~1972)]</a></b>&nbsp;&nbsp;
		<?=$hcat=="봉천동성장기"?"<b>":""?><a href="<?=$_SERVER["PHP_SELF"]?>?hcat=<?=urlencode("봉천동성장기")?>">[봉천동성장기(1972~1993)]</a></b>&nbsp;&nbsp;
		<?=$hcat=="경기도광주성숙기"?"<b>":""?><a href="<?=$_SERVER["PHP_SELF"]?>?hcat=<?=urlencode("경기도광주성숙기")?>">[경기도광주성숙기(1993~2011]</a></b>&nbsp;&nbsp;
		<?=$hcat=="경기도광주신도약기"?"<b>":""?><a href="<?=$_SERVER["PHP_SELF"]?>?hcat=<?=urlencode("경기도광주신도약기")?>">[경기도광주신도약기(2012~<?=date("Y",time())?>]</a></b>&nbsp;&nbsp;
		<table class="list_table mt15">
			<colgroup>
				<col width="5%">
				<col width="10%">
				<col width="">
				<col width="15%">
			</colgroup>
			<thead>
			<tr>
				<th>No</th>
				<th>연/월/일</th>
				<th>내용</th>
				<th>관리</th>
			</tr>
			</thead>
			<?
			$num = $TotalCount - ($page-1)*$board_list_num;
			for ($i=0; $row=sql_fetch_array($result); $i++){
			?>
			<tr>
				<td><?=$i+1;?></td>
				<td><?=$row["hyear"]?> / <?=$row["hmonth"]?> / <?=$row["hday"]?></td>
				<td><?=$row["htext"]?></td>
				<td>
					<div class="floatL pt5" style="width:100%;"><button type="button" class="mbtn_a_n" onclick="admin_modify('HM','<?=$row["idx"]?>');">수 정</button></div>
					<div class="floatL pt5 pb5" style="width:100%;"><button type="button" class="mbtn_a_b" onclick="admin_modify('HD','<?=$row["idx"]?>');">삭 제</button></div>
				</td>
			</tr>
			<?
				$num--;
			}

			if ($i == 0) {
				echo "<tr><td colspan=20 height=100 bgcolor='#ffffff' align=center><span class=point>자료가 한건도 없습니다.</span></td></tr>\n";
			}
			?>
		</table>
		<div class="page_group mt10">
			<div class='page_navi_box'>
				<ul>
					<?
					if($Count>0){
					//	$write_pages = str_replace("처음", "<img src='/board/admn/images/common/start_btn.gif' border='0' align='absmiddle' title='처음'>", $write_pages);
					//	$write_pages = str_replace("이전", "<img src='/board/admn/images/common/prev_btn.gif' border='0' align='absmiddle' title='이전'>", $write_pages);
					//	$write_pages = str_replace("다음", "<img src='/board/admn/images/common/next_btn.gif' border='0' align='absmiddle' title='다음'>", $write_pages);
					//	$write_pages = str_replace("맨끝", "<img src='/board/admn/images/common/end_btn.gif' border='0' align='absmiddle' title='맨끝'>", $write_pages);
						//$write_pages = preg_replace("/<span>([0-9]*)<\/span>/", "$1", $write_pages);
						$write_pages = preg_replace("/<b>([0-9]*)<\/b>/", "<b><span style=\"color:#4D6185; font-size:12px; text-decoration:underline;\">$1</span></b>", $write_pages);
						echo $write_pages;
					}
					?>
				</ul>
			</div>
		</div>
		<div class="cboth"></div>
	</div>
	<div class="mt100"></div>
</div>
<script>
function admin_modify(type,val){
	var f = document.info_form;
	if(type == "HD"){
		if(!confirm("한번 삭제한 자료는 되돌릴 수 없습니다. 삭제하시겠습니까?")){
			return;
		}
		f.idx.value = val;
		f.workType.value = type;
		f.submit();
	} else {
		location.href = "<?=$_SERVER['PHP_SELF']?>?workType="+type+"&idx="+val;
	}
}

function form_ck(){
	var f = document.info_form;
	if(FormCheck(f) == true){
		f.submit();
	} else {
		return;
	}
}
</script>
<?
include_once $dir."/admn/include/tail.php";
?>