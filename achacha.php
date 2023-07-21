<!doctype html>
<html lang='ko'>
	<!----------------------------------
	URL : http://localhost/achacha/achacha.php
	Author : 앗차차_이민지
	summary : achacha.php는 향후 어린이 승하차 시스템을 사용할 웹사이트입니다.
			  웹사이트는 웹캠을 통해 유치원생의 얼굴을 인식하는 영역과 유치원생의 정보를 보여주는 영역, 총 두 개의 영역으로 구성되어 있습니다.
			  현재 기능은 실시간 웹캠 출력, 승하차 모드 선택, DB에 등록된 유치원생 정보 출력, 보호자 전화연결까지 구현되어 있습니다.
			  향후 실시간으로 얼굴을 인식하는 python 코드를 추가하여 웹캠에서 특정 인물을 인식한 뒤, 해당 인물의 정보만 오른쪽 영역에 업데이트 되도록 발전시킬 예정입니다.
	------------------------------------>
	<head>
		<title>앗차차!</title>
		<meta charset="UTF-8">
		<!-- css 파일 : 웹페이지 디자인 파일
			 js 파일 : 승하차모드 변경을 위해 필요한 jquery파일 
			 jpg 파일 : 주소창 아이콘 파일 -->
		<link rel="stylesheet" type="text/css" href="style.css"> 
		<link rel="shortcut icon" type="image/jpg" href="./img/car.jpg"/>
		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	</head>

	<body>
		<!-- 머리글 -->
		<header>
			<h1>🚍앗차차 승하차 시스템🚍</h1>
		</header>
		
		<!-- 본문 시작 -->
		<section>
			<!-- 첫 번째 영역 시작 : 기사님 정보, 실시간 웹캠, 승/하차 버튼 출력 -->
			<article id='display_1'>
				<center>
					<fieldset>
						<legend>📷</legend>
						<form>
							<table>
								<tr>
									<!-- 1. 기사님 정보 -->
									<td style='color : #555548;'>
									<b>* 3호차 / 박 민주 기사님 / 010-4333-1111 </b>
									<button id='phone' onclick="document.location.href='tel:010-4823-7982'">전화걸기</button>*</td><!-- onclick : 전화걸기 버튼 누르면 해당 전화번호로 전화 연결-->
								</tr>
								<tr>
									<!-- 2. 실시간 웹캠 출력 -->
									<td style='width : 400px; height : 300px;'>
									<video id='video' autoplay playsinline></video>
									 <script src="./main.js"></script><br></td><!-- main.js : 웹캠 재생하는 자바스크립트 코드 -->
								</tr>
								<tr>
									<!-- 3. 현재 승/하차 모드 출력 -->
									<td style='color : #555548;'><span><b>* 현재 모드 : 승차 모드 *</b></span></td>
								</tr>
								<tr>
									<!-- 3. 승/하차 모드 선택 버튼 -->
									<td style='letter-spacing : 70px'>
										<input class='ui-btn' type='button' value='승차'></button>
										<input class='ui-btn' type='button' value='하차'></button>
										<script>
											//승/하차 버튼을 중 하나를 선택하면 현재 모드가 승/하차 모드로 변경되게 하는 자바스크립트 코드
											$(".ui-btn").click(function(){
												if( $(this).attr('value') == '승차'){
													$('span').html("<b>* 현재 모드 : <span style='color : #0000ee'>승차</span> 모드 *</b>");
												} else {
													$('span').html("<b>* 현재 모드 : <span style='color : #0000ee'>하차</span> 모드 *</b>");
												}
											});
										</script>
									</td>
								</tr>
							</table>
						</form>
					</fieldset>
				</center>
				<br>
			</article>
			
			<!-- 두 번째 영역 시작 : 승/하차한 유치원생 정보 출력 -->
			<article id='display_2'>
				<center>
					<fieldset>
						<legend>📃</legend>
						<table id='list'>		
								<?php
								/*----------------------------
								summary : 아래의 php 코드는 유치원생 정보가 담긴 데이터베이스와 연결하여 화면에 출력하는 코드입니다.
								-----------------------------*/
								
								//유치원생 이미지 파일 경로를 저장한 리스트
								$img_list = ['./img/minji.png', './img/yunjin.jpg', './img/soobin.jpg', './img/chanhui.jpg', './img/suyeon.jpg'];
								
								//mysql 연결
								$connect = mysqli_connect("localhost", "root", "minsmins59@@", "child_service") or die(mysqli_connect_error());
								
								mysqli_select_db($connect, "child_service");
								
								//SQL 수행 
								$result = mysqli_query($connect,'select * from student;');
								
								//쿼리 실행 후, $query 변수에 담긴 유치원생 정보 출력
								$i = 0;
								while ($row = mysqli_fetch_array($result)) {
									
									echo <<<tab
									<tr>
										<td style='width : 150px;'><img src='{$img_list[$i]}' class='list_img'></td>
										<td style='text-align : left; width : 300px'><b>{$row['name']}({$row['age']}) / {$row['group']} / {$row['blood_type']}</b><br><br>									
											1) 승차 시간 : {$row['geton_time']} <br>
											2) 하차 시간 : {$row['getout_time']} <br>
											3) 보호자번호 : {$row['phone']} <button id='phone' onclick="document.location.href='tel:{$row['phone']}'">전화걸기</button><br>
tab;
									//유치원생 정보에 기타사항이 등록되어 있는 경우, 기타사항까지 출력
									if ($row['notes'] != " ") {
										echo "4) 기타 사항 : {$row['notes']}</td></tr>";
									}
									else {
										echo "</td></tr>";
									}
									
									$i += 1;
								}

								?>
						</table>
					</fieldset>
				</center>
				<br>
			</article>
		</section>
		
		<!-- 바닥글 -->
		<footer>
			<p style='text-align : right; font-size : 12px;'><b>ⓒ 2021. minji lee all rights reserved</b></p>
		</footer>
	</body>
</html>