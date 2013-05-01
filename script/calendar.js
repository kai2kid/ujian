//Javascript name: My Date Time Picker
//Date created: 16-Nov-2003 23:19
//Scripter: TengYong 
//Website: http://www.rainforestnet.com
//Copyright (c) 2003 TengYong Ng
//FileName: DateTimePicker_css.js
//Version: 2.0.2
// Note: Permission given to use and modify this script in ANY kind of applications if
//       header lines are left unchanged.
//Date changed: 24-Dec-2007 by Burgsoft (Holland)
//Changed: Year picker as drop down. Code optimised. Tables filled with blank fields as needed.
//Known (non fatal) issue: javascript remains running after month or year select
//New Css style version added by Yvan Lavoie (Québec, Canada) 29-Jan-2009

//Global variables
var winCalendar;
var dateToday;
var mCal;

var NamaBulan;
var NamaHariMinggu1;
var NamaHariMinggu2;

var exTanggalWaktu;//Existing Date and Time
var TanggalPilih;//selected date. version 1.7

var IDSpanCalendar = "calBorder"; // span ID
var TipeDOM=null; // span DOM object with style
var cordinateKiri="0";//left coordinate of calendar span
var coordinateAtas="0";//top coordinate of calendar span
var PosisiX=0; // mouse x position
var PosisiY=0; // mouse y position
var tinggiCalendar=0; // calendar height
var lebarCalendar=185;// calendar width
var lebarCell=30;// width of day cell.
var pilModeWaktu=24;// pilModeWaktu value. 12 or 24

//Configurable parameters

//var WindowTitle="DateTime Picker";//Date Time Picker title.
var WarnaGarisSpan = "#ededed";//span border color
var WarnaBgSpan = "#ededed";//span background color
var KarakterMinggu=2;//number of character for week day. if 2 then Mo,Tu,We. if 3 then Mon,Tue,Wed.
var PemisahTanggal="-";//Date Separator, you can change it to "-" if you want.
var TampilkanBulanPanjang=true;//Show long month name in Tanggalan header. example: "January".
var TampilkanBulanTahun=true;//Show Month and Year in Tanggalan header.
var WarnaBulanTahun="#cc0033";//Font Color of Month and Year in Tanggalan header.
var WarnaHeaderMinggu="#0d3fa3";//Background Color in Week header.
var WarnaMinggu="#9ebdd9";//Background color of Sunday.
var WarnaSabtu="#9ebdd9";//Background color of Saturday.
var WarnaHariMinggu="ededed";//Background color of weekdays.
var WarnaTulisan="blue";//color of font in Tanggalan day cell.
var WarnaHariIni="#FFFF33";//Background color of today.
var TanggalPilihColor="#8DD53C";//Backgrond color of selected date in textbox.
var WarnaTahunPilih="#cc0033";//color of font of Year selector.
var WarnaBulanPilih="#cc0033";//color of font of Month selector if "MonthSelector" is "arrow".
var WarnaLatar="";//Background image of Tanggalan window.
var WarnaLatarCalendar="ededed";//Backgroud color of Tanggalan window.
var PemberianNol=true;//Preceding zero [true|false]
var BulanPertamaHari=false;//true:Use Monday as first day; false:Sunday as first day. [true|false]  //added in version 1.7
var MenggunakanFileGambar = false;//Use image files with "arrows" and "close" button
//use the Month and Weekday in your preferred language.
//var NamaBulan=["January", "February", "March", "April", "May", "June","July","August", "September", "October", "November", "December"];
var NamaBulan=["Januari", "Februari", "Maret", "April", "Mei", "Juni","Juli","Agustus", "September", "Oktober", "November", "Desember"];
//var NamaHariMinggu1=["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
var NamaHariMinggu1=["Minggu","Senin","Selasa","Rabu","Kamis","Jum'at","Sabtu"];
//var NamaHariMinggu2=["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"];
var NamaHariMinggu2=["Senin","Selasa","Rabu","Kamis","Jum'at","Sabtu","Minggu"];

//end Configurable parameters
//end Global variable

function showCalendarAkademik(pCtrl,pFormat,pScroller,pShowTime,pTimeMode,pHideSeconds) {
    // get current date and time
    dateToday = new Date();
    mCal=new Tanggalan(dateToday);

    if ((pShowTime!=null) && (pShowTime)) {
        mCal.ShowTime=true;
        if ((pTimeMode!=null) &&((pTimeMode=='12')||(pTimeMode=='24')))    {
            pilModeWaktu=pTimeMode;
        }
        else pilModeWaktu='24';

        if (pHideSeconds!=null)
        {
            if (pHideSeconds)
            {mCal.ShowSeconds=false;}
            else
            {mCal.ShowSeconds=true;}
        }
        else
        {
            mCal.ShowSeconds=false;
        }
    }
    if (pCtrl!=null)
        mCal.Ctrl=pCtrl;

    if (pFormat!=null)
        mCal.Format=pFormat.toUpperCase();
    else
        mCal.Format="MMDDYYYY";

    if (pScroller!=null) {
        if (pScroller.toUpperCase()=="ARROW") {
            mCal.Scroller="ARROW";
        }
        else {
            mCal.Scroller="DROPDOWN";
        }
    }
    exTanggalWaktu=document.getElementById(pCtrl).value;

    if (exTanggalWaktu!="")    { //Parse existing Date String
        var Sp1;//Index of Date Separator 1
        var Sp2;//Index of Date Separator 2
        var tSp1;//Index of Time Separator 1
        var tSp1;//Index of Time Separator 2
        var strMonth;
        var strDate;
        var strYear;
        var intMonth;
        var YearPattern;
        var strHour;
        var strMinute;
        var strSecond;
        var winHeight;
        //parse month
        Sp1=exTanggalWaktu.indexOf(PemisahTanggal,0)
        Sp2=exTanggalWaktu.indexOf(PemisahTanggal,(parseInt(Sp1)+1));

        var offset=parseInt(mCal.Format.toUpperCase().lastIndexOf("M"))-parseInt(mCal.Format.toUpperCase().indexOf("M"))-1;
        if ((mCal.Format.toUpperCase()=="DDMMYYYY") || (mCal.Format.toUpperCase()=="DDMMMYYYY")) {
            if (PemisahTanggal=="") {
                strMonth=exTanggalWaktu.substring(2,4+offset);
                strDate=exTanggalWaktu.substring(0,2);
                strYear=exTanggalWaktu.substring(4+offset,8+offset);
            }
            else {
                strMonth=exTanggalWaktu.substring(Sp1+1,Sp2);
                strDate=exTanggalWaktu.substring(0,Sp1);
                strYear=exTanggalWaktu.substring(Sp2+1,Sp2+5);
            }
        }
        else if ((mCal.Format.toUpperCase()=="MMDDYYYY") || (mCal.Format.toUpperCase()=="MMMDDYYYY")) {
            if (PemisahTanggal=="") {
                strMonth=exTanggalWaktu.substring(0,2+offset);
                strDate=exTanggalWaktu.substring(2+offset,4+offset);
                strYear=exTanggalWaktu.substring(4+offset,8+offset);
            }
            else {
                strMonth=exTanggalWaktu.substring(0,Sp1);
                strDate=exTanggalWaktu.substring(Sp1+1,Sp2);
                strYear=exTanggalWaktu.substring(Sp2+1,Sp2+5);
            }
        }
        else if ((mCal.Format.toUpperCase()=="YYYYMMDD") || (mCal.Format.toUpperCase()=="YYYYMMMDD")) {
            if (PemisahTanggal=="") {
                strMonth=exTanggalWaktu.substring(4,6+offset);
                strDate=exTanggalWaktu.substring(6+offset,8+offset);
                strYear=exTanggalWaktu.substring(0,4);
            }
            else {
                strMonth=exTanggalWaktu.substring(Sp1+1,Sp2);
                strDate=exTanggalWaktu.substring(Sp2+1,Sp2+3);
                strYear=exTanggalWaktu.substring(0,Sp1);
            }
        }
        if (isNaN(strMonth))
            intMonth=mCal.DapatkanIndexBulan(strMonth);
        else
            intMonth=parseInt(strMonth,10)-1;
        if ((parseInt(intMonth,10)>=0) && (parseInt(intMonth,10)<12))
            mCal.Month=intMonth;
        //end parse month
        //parse Date
        if ((parseInt(strDate,10)<=mCal.dapatkanBulanHari()) && (parseInt(strDate,10)>=1))
            mCal.Date=strDate;
        //end parse Date
        //parse year
        YearPattern=/^\d{4}$/;
        if (YearPattern.test(strYear))
            mCal.Year=parseInt(strYear,10);
        //end parse year
        //parse time
        if (mCal.ShowTime==true)    {
            //parse AM or PM
            if (pilModeWaktu==12) {
                strAMPM=exTanggalWaktu.substring(exTanggalWaktu.length-2,exTanggalWaktu.length)
                mCal.AMorPM=strAMPM;
            }
            tSp1=exTanggalWaktu.indexOf(":",0)
            tSp2=exTanggalWaktu.indexOf(":",(parseInt(tSp1)+1));
            if (tSp1>0)    {
                strHour=exTanggalWaktu.substring(tSp1,(tSp1)-2);
                mCal.SettingJam(strHour);
                strMinute=exTanggalWaktu.substring(tSp1+1,tSp1+3);
                mCal.SettingMenit(strMinute);
                strSecond=exTanggalWaktu.substring(tSp2+1,tSp2+3);
                mCal.SettingDetik(strSecond);
            }
        }
    }
    TanggalPilih=new Date(mCal.Year,mCal.Month,mCal.Date);//version 1.7
    RanderStyleCalendar(true);
}

function RanderStyleCalendar(bNewCal) {
    if (typeof bNewCal == "undefined" || bNewCal != true) {bNewCal = false;}
    var verCalendarHeader;
    var verCalendarData;
    var verCalendarTime="";
    var i;
    var j;
    var SelectStr;
    var vDayCount=0;
    var vFirstDay;
    tinggiCalendar = 0; // reset the window height on refresh

    // Set the default cursor for the calendar
    windCalendarData="<span style='cursor:auto;'>\n";

    if (WarnaLatar==""){WarnaLatarCalendar="bgcolor='"+WarnaHariMinggu+"'"}

    verCalendarHeader="<table "+WarnaLatarCalendar+" background='"+WarnaLatar+"' border=1 style='border-color:grey;' cellpadding=1 cellspacing=0 width='185' valign='top' align='center' style='margin-left:auto; margin-right:auto;'>\n";
    //Table for Month & Year Selector
    verCalendarHeader+="<tr>\n<td colspan='7'>\n<table border=0 width=185 cellpadding=0 cellspacing=0>\n<tr>\n";

    //******************Month and Year selector in dropdown list************************
    if (mCal.Scroller=="DROPDOWN") {
        verCalendarHeader+="<td align='center'><select name=\"MonthSelector\" onChange=\"javascript:mCal.TukarBulan(this.selectedIndex);RanderStyleCalendar();\">\n";
        for (i=0;i<12;i++) {
            if (i==mCal.Month)
                SelectStr="Selected";
            else
                SelectStr="";
                verCalendarHeader+="<option "+SelectStr+" value="+i+">"+NamaBulan[i]+"</option>\n";
        }
        verCalendarHeader+="</select></td>\n";
        //Year selector
        verCalendarHeader+="<td align='center'><select name=\"YearSelector\" size=\"1\" onChange=\"javascript:mCal.TukarTahun(this.value);RanderStyleCalendar();\">\n";
        for (i = 1950; i < (dateToday.getFullYear() + 5);i++)    {
            if (i==mCal.Year)
                SelectStr="Selected";
            else
                SelectStr="";
            verCalendarHeader+="<option "+SelectStr+" value="+i+">"+i+"</option>\n";
        }
        verCalendarHeader+="</select></td>\n";
        tinggiCalendar += 30;
    }
    //******************End Month and Year selector in dropdown list*********************
    //******************Month and Year selector in arrow*********************************
    else if (mCal.Scroller=="ARROW")
  {
    if (MenggunakanFileGambar)
    {
          verCalendarHeader+="<td><img onmousedown='javascript:mCal.KurangTahun();RanderStyleCalendar();' src='images/cal_fastreverse.gif' width='13' height='9' onmouseover='gantiBorder(this, 0)' onmouseout='gantiBorder(this, 1)' style='border:1px solid white'></td>\n";//Year scroller (decrease 1 year)
          verCalendarHeader+="<td><img onmousedown='javascript:mCal.KurangBulan();RanderStyleCalendar();' src='images/cal_reverse.gif' width='13' height='9' onmouseover='gantiBorder(this, 0)' onmouseout='gantiBorder(this, 1)' style='border:1px solid white'></td>\n";//Month scroller (decrease 1 month)
          verCalendarHeader+="<td width='70%' class='calR'><font color='"+WarnaTahunPilih+"'>"+mCal.GetNamaBulan(TampilkanBulanPanjang)+" "+mCal.Year+"</font></td>\n"//Month and Year
          verCalendarHeader+="<td><img onmousedown='javascript:mCal.TambahBulan();RanderStyleCalendar();' src='images/cal_forward.gif' width='13' height='9' onmouseover='gantiBorder(this, 0)' onmouseout='gantiBorder(this, 1)' style='border:1px solid white'></td>\n";//Month scroller (increase 1 month)
          verCalendarHeader+="<td><img onmousedown='javascript:mCal.TambahTahun();RanderStyleCalendar();' src='images/cal_fastforward.gif' width='13' height='9' onmouseover='gantiBorder(this, 0)' onmouseout='gantiBorder(this, 1)' style='border:1px solid white'></td>\n";//Year scroller (increase 1 year)
          tinggiCalendar += 22;
      }
      else
      {
          verCalendarHeader+="<td><span id='dec_year' title='reverse year' onmousedown='javascript:mCal.KurangTahun();RanderStyleCalendar();' onmouseover='gantiBorder(this, 0)' onmouseout='gantiBorder(this, 1)' style='border:1px solid white; color:"+WarnaTahunPilih+"'>-</span></td>";//Year scroller (decrease 1 year)
          verCalendarHeader+="<td><span id='dec_month' title='reverse month' onmousedown='javascript:mCal.KurangBulan();RanderStyleCalendar();' onmouseover='gantiBorder(this, 0)' onmouseout='gantiBorder(this, 1)' style='border:1px solid white'>&lt;</span></td>\n";//Month scroller (decrease 1 month)
          verCalendarHeader+="<td width='70%' class='calR'><font color='"+WarnaTahunPilih+"'>"+mCal.GetNamaBulan(TampilkanBulanPanjang)+" "+mCal.Year+"</font></td>\n"//Month and Year
          verCalendarHeader+="<td><span id='inc_month' title='forward month' onmousedown='javascript:mCal.TambahBulan();RanderStyleCalendar();' onmouseover='gantiBorder(this, 0)' onmouseout='gantiBorder(this, 1)' style='border:1px solid white'>&gt;</span></td>\n";//Month scroller (increase 1 month)
          verCalendarHeader+="<td><span id='inc_year' title='forward year' onmousedown='javascript:mCal.TambahTahun();RanderStyleCalendar();'  onmouseover='gantiBorder(this, 0)' onmouseout='gantiBorder(this, 1)' style='border:1px solid white; color:"+WarnaTahunPilih+"'>+</span></td>\n";//Year scroller (increase 1 year)
          tinggiCalendar += 22;
      }
    }
    verCalendarHeader+="</tr>\n</table>\n</td>\n</tr>\n"
  //******************End Month and Year selector in arrow******************************
    //Tanggalan header shows Month and Year
    if ((TampilkanBulanTahun)&&(mCal.Scroller=="DROPDOWN")) {
        verCalendarHeader+="<tr><td colspan='7' class='calR'>\n<font color='"+WarnaBulanTahun+"'>"+mCal.GetNamaBulan(TampilkanBulanPanjang)+" "+mCal.Year+"</font>\n</td></tr>\n";
        tinggiCalendar += 19;
    }
    //Week day header
    verCalendarHeader+="<tr bgcolor="+WarnaHeaderMinggu+">\n";
    var WeekDayName=new Array();//Added version 1.7
    if (BulanPertamaHari==true)
        WeekDayName=NamaHariMinggu2;
    else
        WeekDayName=NamaHariMinggu1;
    for (i=0;i<7;i++) {
        verCalendarHeader+="<td width='"+lebarCell+"' class='calTD'><font color='white'>"+WeekDayName[i].substr(0,KarakterMinggu)+"</font></td>\n";
    }
    tinggiCalendar += 19;
    verCalendarHeader+="</tr>\n";
    //Tanggalan detail
    CalDate=new Date(mCal.Year,mCal.Month);
    CalDate.setDate(1);
    vFirstDay=CalDate.getDay();
    //Added version 1.7
    if (BulanPertamaHari==true) {
        vFirstDay-=1;
        if (vFirstDay==-1)
            vFirstDay=6;
    }
    //Added version 1.7
    verCalendarData="<tr>";
    tinggiCalendar += 19;
    for (i=0;i<vFirstDay;i++) {
        verCalendarData=verCalendarData+GenerateCell();
        vDayCount=vDayCount+1;
    }
    //Added version 1.7
    for (j=1;j<=mCal.dapatkanBulanHari();j++) {
        var strCell;
        if((vDayCount%7==0)&&(j > 1)) {
            verCalendarData=verCalendarData+"\n<tr>";
        }
        vDayCount=vDayCount+1;
        if ((j==dateToday.getDate())&&(mCal.Month==dateToday.getMonth())&&(mCal.Year==dateToday.getFullYear()))
            strCell=GenerateCell(j,true,WarnaHariIni);//Highlight today's date
        else {
            if ((j==TanggalPilih.getDate())&&(mCal.Month==TanggalPilih.getMonth())&&(mCal.Year==TanggalPilih.getFullYear())) { //modified version 1.7
                strCell=GenerateCell(j,true,TanggalPilihColor);
            }
            else {
                if (BulanPertamaHari==true) {
                    if (vDayCount%7==0)
                        strCell=GenerateCell(j,false,WarnaMinggu);
                    else if ((vDayCount+1)%7==0)
                        strCell=GenerateCell(j,false,WarnaSabtu);
                    else
                        strCell=GenerateCell(j,null,WarnaHariMinggu);
                }
                else {
                    if (vDayCount%7==0)
                        strCell=GenerateCell(j,false,WarnaSabtu);
                    else if ((vDayCount+6)%7==0)
                        strCell=GenerateCell(j,false,WarnaMinggu);
                    else
                        strCell=GenerateCell(j,null,WarnaHariMinggu);
                }
            }
        }
        verCalendarData=verCalendarData+strCell;

        if((vDayCount%7==0)&&(j<mCal.dapatkanBulanHari())) {
            verCalendarData=verCalendarData+"\n</tr>";
            tinggiCalendar += 19;
        }
    }
    // finish the table proper
    if(!(vDayCount%7) == 0) {
        while(!(vDayCount % 7) == 0) {
            verCalendarData=verCalendarData+GenerateCell();
            vDayCount=vDayCount+1;
        }
    }
    verCalendarData=verCalendarData+"\n</tr>";

    //Time picker
    if (mCal.ShowTime)
    {
        var showHour;
        var ShowArrows=false;
        var HourCellWidth="35px"; //cell width with seconds.
        showHour=mCal.dapatkanTampilanJam();

        if (mCal.ShowSeconds==false && pilModeWaktu==24 )
        {
           ShowArrows=true;
           HourCellWidth="10px";
        }

        verCalendarTime="\n<tr>\n<td colspan='7' align='center'><center>\n<table border='0' width='185px' cellpadding='0' cellspacing='0' align='center'>\n<tr>\n<td height='5px' width='"+HourCellWidth+"'>&nbsp;</td>\n";

        if (ShowArrows && MenggunakanFileGambar)
        {
            verCalendarTime+="<td align='center'><table cellspacing='0' cellpadding='0' style='line-height:0pt'><tr><td><img onmousedown='javascript:mCal.SettingJam(mCal.Hours + 1);RanderStyleCalendar();' src='images/cal_plus.gif' width='13' height='9' onmouseover='gantiBorder(this, 0)' onmouseout='gantiBorder(this, 1)' style='border:1px solid white'></td></tr><tr><td><img onmousedown='javascript:mCal.SettingJam(mCal.Hours - 1);RanderStyleCalendar();' src='images/cal_minus.gif' width='13' height='9' onmouseover='gantiBorder(this, 0)' onmouseout='gantiBorder(this, 1)' style='border:1px solid white'></td></tr></table></td>\n";
        }

        verCalendarTime+="<td align='center' width='22px'><input type='text' name='hour' maxlength=2 size=1 style=\"WIDTH: 22px\" value="+showHour+" onChange=\"javascript:mCal.SettingJam(this.value)\">";
        verCalendarTime+="</td><td align='center'>:</td><td align='center' width='22px'>";
        verCalendarTime+="<input type='text' name='minute' maxlength=2 size=1 style=\"WIDTH: 22px\" value="+mCal.Minutes+" onChange=\"javascript:mCal.SettingMenit(this.value)\">";

        if (mCal.ShowSeconds) {
            verCalendarTime+="</td><td align='center'>:</td><td align='center' width='22px'>";
            verCalendarTime+="<input type='text' name='second' maxlength=2 size=1 style=\"WIDTH: 22px\" value="+mCal.Seconds+" onChange=\"javascript:mCal.SettingDetik(parseInt(this.value,10))\">";
        }
        if (pilModeWaktu==12) {
            var SelectAm =(mCal.AMorPM=="AM")? "Selected":"";
            var SelectPm =(mCal.AMorPM=="PM")? "Selected":"";

            verCalendarTime+="</td><td>";
            verCalendarTime+="<select name=\"ampm\" onChange=\"javascript:mCal.SettingSiangMalam(this.options[this.selectedIndex].value);\">\n";
            verCalendarTime+="<option "+SelectAm+" value=\"AM\">AM</option>";
            verCalendarTime+="<option "+SelectPm+" value=\"PM\">PM<option>";
            verCalendarTime+="</select>";
        }
        if (ShowArrows && MenggunakanFileGambar) {
           verCalendarTime+="</td>\n<td align='center'><table cellspacing='0' cellpadding='0' style='line-height:0pt'><tr><td><img onmousedown='javascript:mCal.SettingMenit(parseInt(mCal.Minutes,10) + 1);RanderStyleCalendar();' src='images/cal_plus.gif' width='13px' height='9px' onmouseover='gantiBorder(this, 0)' onmouseout='gantiBorder(this, 1)' style='border:1px solid white'></td></tr><tr><td><img onmousedown='javascript:mCal.SettingMenit(parseInt(mCal.Minutes,10) - 1);RanderStyleCalendar();' src='images/cal_minus.gif' width='13px' height='9px' onmouseover='gantiBorder(this, 0)' onmouseout='gantiBorder(this, 1)' style='border:1px solid white'></td></tr></table>";
        }
        verCalendarTime+="</td>\n<td align='right' valign='bottom' width='"+HourCellWidth+"'>";

    }
    //else
        //{verCalendarTime+="\n<tr>\n<td colspan='7' align='right'>";}
    /*if (MenggunakanFileGambar)
    {
        verCalendarTime+="<img onmousedown='javascript:tutupWindow(\"" + mCal.Ctrl + "\");' src='images/cal_close.gif' width='16' height='14' onmouseover='gantiBorder(this, 0)' onmouseout='gantiBorder(this, 1)' style='border:1px solid white'></td>";
    }
    else
    {
        verCalendarTime+="<span id='close_cal' title='close' onmousedown='javascript:tutupWindow(\"" + mCal.Ctrl + "\");' onmouseover='gantiBorder(this, 0)' onmouseout='gantiBorder(this, 1)' style='border:1px solid white; font-family: Arial;font-size: 10pt;'>x</span></td>";
    } */
    //verCalendarTime+="&nbsp;</td>";

    verCalendarTime+="</tr>\n</table></center>\n</td>\n</tr>";
    tinggiCalendar += 31;
    verCalendarTime+="\n</table>\n</span>";

    //end time picker
    var funcCalback="function callback(id, datum) {\n";
    funcCalback+=" var CalId = document.getElementById(id); CalId.value=datum;\n";
    funcCalback+=" if (CalId.onchange != null && CalId.onchange != 'undefined') { CalId.onchange(); }\n";
    funcCalback+=" if (mCal.ShowTime) {\n";
    funcCalback+=" CalId.value+=' '+mCal.dapatkanTampilanJam()+':'+mCal.Minutes;\n";
    funcCalback+=" if (mCal.ShowSeconds)\n  CalId.value+=':'+mCal.Seconds;\n";
    funcCalback+=" if (pilModeWaktu==12)\n  CalId.value+=' '+mCal.dapatkanTampilanSiangAtoMalam();\n";
    funcCalback+="}\n CalId.focus(); \n winCalendar.style.visibility='hidden';\n}\n";

    // determines if there is enough space to open the cal above the position where it is called
    if (PosisiY > tinggiCalendar) {
       PosisiY = PosisiY - tinggiCalendar;
    }
    if (winCalendar == undefined) {
       var headID = document.getElementsByTagName("head")[0];

       // add javascript function to the span cal
       var e = document.createElement("script");
       e.type = "text/javascript";
       e.language = "javascript";
       e.text = funcCalback;
       headID.appendChild(e);

       // add stylesheet to the span cal
       var cssStr = ".calTD {font-family: verdana; font-size: 12px; text-align: center;}\n";
       cssStr+= ".calR {font-family: verdana; font-size: 12px; text-align: center; font-weight: bold; color: red;}"
       var style = document.createElement("style");
       style.type = "text/css";
       style.rel = "stylesheet";
       if(style.styleSheet) { // IE
          style.styleSheet.cssText = cssStr;
        }
       else { // w3c
          var cssText = document.createTextNode(cssStr);
          style.appendChild(cssText);
        }
       headID.appendChild(style);

       // create the outer frame that allows the cal. to be moved
       var span = document.createElement("span");
       span.id = IDSpanCalendar;

       with (span.style) {position = "relative"; width = "auto"; cursor = "move"; backgroundColor = WarnaBgSpan; zIndex = 0;}

       document.body.appendChild(span)
       winCalendar=document.getElementById(IDSpanCalendar);
    }
    else {
      winCalendar.style.visibility = "visible";
      winCalendar.style.Height = tinggiCalendar;

      // set the position for a new calendar only      
    }
    //winCalendar.innerHTML=windCalendarData + verCalendarHeader + verCalendarData + verCalendarTime;
    $("#myCalendar").html(windCalendarData + verCalendarHeader + verCalendarData + verCalendarTime);
    return true;
}

function GenerateCell(pValue,pHighLight,pColor) { //Generate table cell with value
    var PValue;
    var PCellStr;
    var vColor;
    var vHLstr1;//HighLight string
    var vHlstr2;
    var vTimeStr;

    if (pValue==null)
        PValue="";
    else
        PValue=pValue;

    if (pColor!=null)
        vColor="bgcolor=\""+pColor+"\"";
    else
        vColor=WarnaLatarCalendar;
        if ((pHighLight!=null)&&(pHighLight)) {
           vHLstr1="<font class='calR'>";vHLstr2="</font>";
         }
        else {
           vHLstr1="";vHLstr2="";
         }
    if (mCal.ShowTime) {
        vTimeStr=' '+mCal.Hours+':'+mCal.Minutes;
        if (mCal.ShowSeconds)
            vTimeStr+=':'+mCal.Seconds;
        if (pilModeWaktu==12)
            vTimeStr+=' '+mCal.AMorPM;
    }
    else
        vTimeStr="";
    if (PValue!="")
        PCellStr="\n<td "+vColor+" class='calTD'>"+vHLstr1+PValue+vHLstr2+"</td>";
    else
        PCellStr="\n<td "+vColor+" class='calTD'>&nbsp;</td>";
    return PCellStr;
}

function Tanggalan(pDate,pCtrl) {
    //Properties
    this.Date=pDate.getDate();//selected date
    this.Month=pDate.getMonth();//selected month number
    this.Year=pDate.getFullYear();//selected year in 4 digits
    this.Hours=pDate.getHours();

    if (pDate.getMinutes()<10)
        this.Minutes="0"+pDate.getMinutes();
    else
        this.Minutes=pDate.getMinutes();

    if (pDate.getSeconds()<10)
        this.Seconds="0"+pDate.getSeconds();
    else
        this.Seconds=pDate.getSeconds();

    this.MyWindow=winCalendar;
    this.Ctrl=pCtrl;
    this.Format="ddMMyyyy";
    this.Separator=PemisahTanggal;
    this.ShowTime=false;
    this.Scroller="DROPDOWN";
    if (pDate.getHours()<12)
        this.AMorPM="AM";
    else
        this.AMorPM="PM";
    this.ShowSeconds=true;
}

function DapatkanIndexBulan(shortNamaBulan) {
    for (i=0;i<12;i++) {
        if (NamaBulan[i].substring(0,3).toUpperCase()==shortNamaBulan.toUpperCase())
           {return i;}
    }
}
Tanggalan.prototype.DapatkanIndexBulan=DapatkanIndexBulan;

function TambahTahun() {
    mCal.Year++;}
    Tanggalan.prototype.TambahTahun=TambahTahun;

function KurangTahun() {
    mCal.Year--;}
    Tanggalan.prototype.KurangTahun=KurangTahun;

function TambahBulan() {
    mCal.Month++;
    if (mCal.Month>=12) {
        mCal.Month=0;
        mCal.TambahTahun();
    }
}
Tanggalan.prototype.TambahBulan=TambahBulan;

function KurangBulan() {
    mCal.Month--;
    if (mCal.Month<0) {
        mCal.Month=11;
        mCal.KurangTahun();
    }
}
Tanggalan.prototype.KurangBulan=KurangBulan;

function TukarBulan(intMth) {
    mCal.Month=intMth;}
    Tanggalan.prototype.TukarBulan=TukarBulan;

function TukarTahun(intYear) {
    mCal.Year=intYear;}
    Tanggalan.prototype.TukarTahun=TukarTahun;

function SettingJam(intHour) {
    var MaxHour;
    var MinHour;
    if (pilModeWaktu==24) {
        MaxHour=23;MinHour=0}
    else if (pilModeWaktu==12) {
        MaxHour=12;MinHour=1}
    else
        alert("pilModeWaktu can only be 12 or 24");
    var HourExp=new RegExp("^\\d\\d");
    var SingleDigit=new RegExp("\\d");

    if ((HourExp.test(intHour) || SingleDigit.test(intHour)) && (parseInt(intHour,10)>MaxHour)) {
        intHour = MinHour;
    }
    else if ((HourExp.test(intHour) || SingleDigit.test(intHour)) && (parseInt(intHour,10)<MinHour)) {
        intHour = MaxHour;
    }

    if (SingleDigit.test(intHour)) {
        intHour="0"+intHour+"";
    }

    if (HourExp.test(intHour) && (parseInt(intHour,10)<=MaxHour) && (parseInt(intHour,10)>=MinHour)) {
        if ((pilModeWaktu==12) && (mCal.AMorPM=="PM")) {
            if (parseInt(intHour,10)==12)
                mCal.Hours=12;
            else
                mCal.Hours=parseInt(intHour,10)+12;
        }
        else if ((pilModeWaktu==12) && (mCal.AMorPM=="AM")) {
            if (intHour==12)
                intHour-=12;
            mCal.Hours=parseInt(intHour,10);
        }
        else if (pilModeWaktu==24)
            mCal.Hours=parseInt(intHour,10);
    }
}
Tanggalan.prototype.SettingJam=SettingJam;

function SettingMenit(intMin) {
    var MaxMin=59;
    var MinMin=0;
    var SingleDigit=new RegExp("\\d");
    var SingleDigit2=new RegExp("^\\d{1}$");
    var MinExp=new RegExp("^\\d{2}$");

    if ((MinExp.test(intMin) || SingleDigit.test(intMin)) && (parseInt(intMin,10)>MaxMin)) {
        intMin = MinMin;
    }
    else if ((MinExp.test(intMin) || SingleDigit.test(intMin)) && (parseInt(intMin,10)<MinMin))    {
        intMin = MaxMin;
    }
    var strMin = intMin + "";
    if (SingleDigit2.test(intMin)) {
        strMin="0"+strMin+"";
    }
    if ((MinExp.test(intMin) || SingleDigit.test(intMin))
     && (parseInt(intMin,10)<=59) && (parseInt(intMin,10)>=0)) {
         mCal.Minutes=strMin;
    }
}
Tanggalan.prototype.SettingMenit=SettingMenit;

function SettingDetik(intSec) {
    var MaxSec=59;
    var MinSec=0;
    var SingleDigit=new RegExp("\\d");
    var SingleDigit2=new RegExp("^\\d{1}$");
    var SecExp=new RegExp("^\\d{2}$");

    if ((SecExp.test(intSec) || SingleDigit.test(intSec)) && (parseInt(intSec,10)>MaxSec)) {
        intSec = MinSec;
    }
    else if ((SecExp.test(intSec) || SingleDigit.test(intSec)) && (parseInt(intSec,10)<MinSec))    {
        intSec = MaxSec;
    }
    var strSec = intSec + "";
    if (SingleDigit2.test(intSec)) {
        strSec="0"+strSec+"";
    }
    if ((SecExp.test(intSec) || SingleDigit.test(intSec))
     && (parseInt(intSec,10)<=59) && (parseInt(intSec,10)>=0)) {
         mCal.Seconds=strSec;
    }
}
Tanggalan.prototype.SettingDetik=SettingDetik;

function SettingSiangMalam(pvalue) {
    this.AMorPM=pvalue;
    if (pvalue=="PM") {
        this.Hours=(parseInt(this.Hours,10))+12;
        if (this.Hours==24)
            this.Hours=12;
    }
    else if (pvalue=="AM")
        this.Hours-=12;
}
Tanggalan.prototype.SettingSiangMalam=SettingSiangMalam;

function dapatkanTampilanJam() {
    var finalHour;
    if (pilModeWaktu==12) {
        if (parseInt(this.Hours,10)==0) {
            this.AMorPM="AM";
            finalHour=parseInt(this.Hours,10)+12;
        }
        else if (parseInt(this.Hours,10)==12) {
            this.AMorPM="PM";
            finalHour=12;
        }
        else if (this.Hours>12)    {
            this.AMorPM="PM";
            if ((this.Hours-12)<10)
                finalHour="0"+((parseInt(this.Hours,10))-12);
            else
                finalHour=parseInt(this.Hours,10)-12;
        }
        else {
            this.AMorPM="AM";
            if (this.Hours<10)
                finalHour="0"+parseInt(this.Hours,10);
            else
                finalHour=this.Hours;
        }
    }
    else if (pilModeWaktu==24) {
        if (this.Hours<10)
            finalHour="0"+parseInt(this.Hours,10);
        else
            finalHour=this.Hours;
    }
    return finalHour;
}
Tanggalan.prototype.dapatkanTampilanJam=dapatkanTampilanJam;

function dapatkanTampilanSiangAtoMalam() {
    return this.AMorPM;
}
Tanggalan.prototype.dapatkanTampilanSiangAtoMalam=dapatkanTampilanSiangAtoMalam;

function GetNamaBulan(IsLong) {
    var Month=NamaBulan[this.Month];
    if (IsLong)
        return Month;
    else
        return Month.substr(0,3);
}
Tanggalan.prototype.GetNamaBulan=GetNamaBulan;

function dapatkanBulanHari() { //Get number of days in a month
    var DaysInMonth=[31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
    if (this.apakahLeapTahun()) {
        DaysInMonth[1]=29;
    }
    return DaysInMonth[this.Month];
}
Tanggalan.prototype.dapatkanBulanHari=dapatkanBulanHari;

function apakahLeapTahun() {
    if ((this.Year%4)==0) {
        if ((this.Year%100==0) && (this.Year%400)!=0) {
            return false;
        }
        else {
            return true;
        }
    }
    else {
        return false;
    }
}
Tanggalan.prototype.apakahLeapTahun=apakahLeapTahun;

function FormatTanggal(pDate)
{
    var MonthDigit=this.Month+1;
    if (PemberianNol==true) {
        if (pDate<10)
            pDate="0"+pDate;
        if (MonthDigit<10)
            MonthDigit="0"+MonthDigit;
    }

    if (this.Format.toUpperCase()=="DDMMYYYY")
        return (pDate+PemisahTanggal+MonthDigit+PemisahTanggal+this.Year);
    else if (this.Format.toUpperCase()=="DDMMMYYYY")
        return (pDate+PemisahTanggal+this.GetNamaBulan(false)+PemisahTanggal+this.Year);
    else if (this.Format.toUpperCase()=="MMDDYYYY")
        return (MonthDigit+PemisahTanggal+pDate+PemisahTanggal+this.Year);
    else if (this.Format.toUpperCase()=="MMMDDYYYY")
        return (this.GetNamaBulan(false)+PemisahTanggal+pDate+PemisahTanggal+this.Year);
    else if (this.Format.toUpperCase()=="YYYYMMDD")
        return (this.Year+PemisahTanggal+MonthDigit+PemisahTanggal+pDate);
    else if (this.Format.toUpperCase()=="YYYYMMMDD")
        return (this.Year+PemisahTanggal+this.GetNamaBulan(false)+PemisahTanggal+pDate);
    else
        return (pDate+PemisahTanggal+(this.Month+1)+PemisahTanggal+this.Year);
}
Tanggalan.prototype.FormatTanggal=FormatTanggal;

function tutupWindow(id) {
   var CalId = document.getElementById(id);
   CalId.focus();
   winCalendar.style.visibility='hidden';
 }

function gantiBorder(element, col) {
  if (col == 0) {
    element.style.borderColor = "black";
    element.style.cursor = "pointer";
  }
  else {
    element.style.borderColor = "white";
    element.style.cursor = "auto";
  }
}

