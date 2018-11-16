# < Web_Project > Make simple web application using HTML/CSS and Java-script

Environment : Sublime in Windows, Joomla(CMS)/Wampserver
Language : PHP, HTML/CSS, Java-Script, MySQL

<br><br>

# Runner Game - 2016.12
<p> Web Browser에서 동작하는 Runner Game을 구현 </p>

#### Program Structure


- 실행가능 : Chrome / Firefox
- 실행불가능 : Internet Explore / Microsoft Edge (로컬 스토리지 지원X)

<br><br>

# Stock Chart - 2016.7
<p> Yahoo에서 제공하는 Stock정보를 받아와 Chart를 구성 </p>
<p> 개발 시에는 Joomla로 만들 웹페이지에서 동작하는 Module을 제작 </p>
<p> dnjsgksms Stock종류와 전체적 Module의 색상은 Joomla Manager Mode에서 지정 가능</p>



#### Program Function
	Stoke info는 close, volume, date, open, low, high로 구성되며 Area chart에서는 close, date info를 다루고 stick chart에서는 volume, date info를 다룬다.
	10 days view, 1 month view, 3 months view, 1 year view button을 통해 해당 기간별 stoke info를 area chart로 구현 <br>
(1 month, 3 months, 1 year의 경우 stoke info가 많아 모든 정보를 표현하게 되면 chart가 보기 좋지 않기 때문에 1 week 단위로 보여준다.)
	input date를 이용해 From ~ To 기간을 선택하게 되면 그 기간 동안의 stoke info를 출력
	scroll slider를 이용해 드래그를 하게 되면 slider의 위치에 따른 chart를 출력
	chart의 Y축값은 close이며 구간별 close값에 따라 max가 정해져 chart의 Y값 gap은 동적으로 정해진다.
	volume의 경우도 close와 마찬가지로 max값을 구하고 이에 따른 stick의 크기(높이)가 정해지고 이것의 gap도 동적으로 정해진다.
	Chart는 hover event를 기본으로 하며 이를 이용해 chart의 close, volume, date info를 마우스 위치에 따라 확인할 수 있다.
	Area, stick chart의 아랫부분에는 date를 select를 이용해 선택하고 look up button을 클릭하게 되면 이 date에 따른 close, date, open, volume, high, low값을 표로 확인할 수 있다.
	Warmserver를 통해 chart의 color를 동적으로 바꿀 수 있다.
	하나의 webpage에서 여러 개의 module이 동작할 수 있다. <br>
이는 stoke info를 yahoo에서 불러와 Database에 저장하고 java-script의 AJAX을 이용해 server에서 DB정보를 불러와 JSON Array로 저장하여 stoke information을 chart 사용 중에 갱신시켜 줌으로서 동작 가능하다.
