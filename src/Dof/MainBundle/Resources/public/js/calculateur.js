//Fonctions de calcule

function calculCc()
{
	var val1,val2,val3,val4,total;
	total=parseInt(document.cc.ccbase.value);	
	if(isNaN(total)){total = 1}

	val2=parseInt(document.cc.ccbonus.value);
	if(isNaN(val2)){val2 = 0;}

	val1 = total -val2;
	val3=parseInt(document.cc.agi.value);

	if(val3>7){
		val4 =val1*2.9901/Math.log(val3 +12);
	}
	else{val4=val1;}

	if(val4<2){val4 =2;}
	document.getElementById("cc").innerHTML = Math.floor(val4);
}
calculCc();

function calculpods()
{
	var force,bonus,metier1,metier2,metier3,specialisation1,specialisation2,specialisation3,metier,totalmetier,total;
	force=parseInt(document.pods.force.value); ;
	if(isNaN(force)){force = 0;}
	bonus=parseInt(document.pods.bonus.value);
	if(isNaN(bonus)){bonus = 0;}
	metier1=parseInt(document.pods.metier1.value);
	if(isNaN(metier1)){metier1=0;}
	if(metier1>100){
		metier1=100;
		document.pods.metier1.value = 100;
	}
	metier2=parseInt(document.pods.metier2.value);
	if(isNaN(metier2)){metier2=0;}
	if(metier2>100){
		metier2=100;
		document.pods.metier2.value = 100;
	}
	metier3=parseInt(document.pods.metier3.value);
	if(isNaN(metier3)){metier3=0;}
	if(metier3>100){
		metier3=100;
		document.pods.metier3.value = 100;
	}
	metier=0;
	specialisation1=parseInt(document.pods.specialisation1.value);
	if(isNaN(specialisation1)){specialisation1=0;}
	if(specialisation1>100){
		specialisation1=100;
		document.pods.specialisation1.value = 100;
	}
	specialisation2=parseInt(document.pods.specialisation2.value);
	if(isNaN(specialisation2)){specialisation2=0;}
	if(specialisation2>100){
		specialisation2=100;
		document.pods.specialisation2.value = 100;
	}
	specialisation3=parseInt(document.pods.specialisation3.value);
	if(isNaN(specialisation3)){specialisation3=0;}if(specialisation3>100){specialisation3=100;
	document.pods.specialisation3.value = 100;}
	if(metier1==100){metier++;}
	if(metier2==100){metier++;}
	if(metier3==100){metier++;}
	if(specialisation1==100){metier++;}
	if(specialisation2==100){metier++;}
	if(specialisation3==100){metier++;}
	totalmetier = metier1 + metier2+metier3+specialisation1+specialisation2+specialisation3;
	total = 1000 + force * 5 + totalmetier * 5 + metier * 1000 + bonus;
	document.getElementById("pods").innerHTML = Math.floor(total);

}
calculpods();

function calculini()
{
	var val1,val2,val3,val4,totalcaract,bonusini,pvactuel,pvmax;
	val1 = parseInt(document.ini.force.value);

	if(isNaN(val1))
	{val1 = 0;}
	val2 = parseInt(document.ini.eau.value);
	if(isNaN(val2))
	{val2 = 0;}
	val3 = parseInt(document.ini.feu.value);
	if(isNaN(val3))
	{val3 = 0;}
	val4 = parseInt(document.ini.agi.value);
	if(isNaN(val4))
	{val4 = 0;}
	bonusini = parseInt(document.ini.bonusini.value);
	if(isNaN(bonusini))
	{bonusini = 0;}
	pvactuel = parseInt(document.ini.pvactuel.value);
	if(isNaN(pvactuel))
	{pvactuel = 0;}
	pvmax = parseInt(document.ini.pvmax.value);
	if(isNaN(pvmax))
	{pvmax = 0;}
	totalcaract = val1 + val2 + val3 + val4
	document.getElementById("initiative").innerHTML = (totalcaract + bonusini) * (pvactuel/pvmax)
}

// Fonctions cacher les divs

/*
* Montre / Cache un div
*/
function DivStatus( nom, numero ){
	var divID = nom + numero;
	if ( document.getElementById && document.getElementById( divID ) ) // Pour les navigateurs récents
	{
		Pdiv = document.getElementById( divID );PcH = true;
	}
	else if ( document.all && document.all[ divID ] ) // Pour les veilles versions
	{
		Pdiv = document.all[ divID ];PcH = true;
	}
	else if ( document.layers && document.layers[ divID ] ) // Pour les très veilles versions
	{
		Pdiv = document.layers[ divID ];PcH = true;
	}
	else
	{
		PcH = false;
	}
	if ( PcH )
	{	
		Pdiv.className = ( Pdiv.className == 'cachediv' ) ? '' : 'cachediv';
	}
}
			
function CacheTout( nom )
{
	var NumDiv = 1;
	if ( document.getElementById ) // Pour les navigateurs récents
	{
		while ( document.getElementById( nom + NumDiv) )
		{
			SetDiv = document.getElementById( nom + NumDiv );
			if ( SetDiv && SetDiv.className != 'cachediv' )
			{
				DivStatus( nom, NumDiv );
			}NumDiv++;
		}
	}
	else if ( document.all ) // Pour les veilles versions
	{
		while ( document.all[ nom + NumDiv ] )
		{
			SetDiv = document.all[ nom + NumDiv ];
			if ( SetDiv && SetDiv.className != 'cachediv' )
			{
				DivStatus( nom, NumDiv );
			}
			NumDiv++;
		}
	}
	else if ( document.layers ) // Pour les très veilles versions
	{
		while ( document.layers[ nom + NumDiv ] )
		{
			SetDiv = document.layers[ nom + NumDiv ];
			if ( SetDiv && SetDiv.className != 'cachediv' )
			{
				DivStatus( nom, NumDiv );
			}
			NumDiv++;
		}
	}
}
function MontreTout( nom )
{
	var NumDiv = 1;
	if ( document.getElementById ) // Pour les navigateurs récents
	{
		while ( document.getElementById( nom + NumDiv) )
		{
			SetDiv = document.getElementById( nom + NumDiv );
			if ( SetDiv && SetDiv.className != '' )
			{
				DivStatus( nom, NumDiv );
			}
			NumDiv++;
		}
	}
	else if ( document.all ) // Pour les veilles versions
	{
		while ( document.all[ nom + NumDiv ] )
		{
			SetDiv = document.all[ nom + NumDiv ];
			if ( SetDiv && SetDiv.className != '' )
			{
				DivStatus( nom, NumDiv );
			}
			NumDiv++;
		}
	}
	else if ( document.layers ) // Pour les très veilles versions
	{
		while ( document.layers[ nom + NumDiv ] )
		{
			SetDiv = document.layers[ nom + NumDiv ];
			if ( SetDiv && SetDiv.className != '' )
			{
				DivStatus( nom, NumDiv );
			}
			NumDiv++;
		}
	}
}
function InverseTout( nom )
{
	var NumDiv = 1;
	if ( document.getElementById ) // Pour les navigateurs récents
	{
		while ( document.getElementById( nom + NumDiv ) )
		{
			SetDiv = document.getElementById( nom + NumDiv );
			DivStatus( nom, NumDiv );
			NumDiv++;
		}
	}
	else if ( document.all ) // Pour les veilles versions
	{
		while ( document.all[ nom + NumDiv ] )
		{
			SetDiv = document.all[ nom + NumDiv ];
			DivStatus( nom, NumDiv );NumDiv++;
		}
	}
	else if ( document.layers ) // Pour les très veilles versions
	{
		while ( document.layers[ nom + NumDiv ] )
		{
			SetDiv = document.layers[ nom + NumDiv ];
			DivStatus( nom, NumDiv );
			NumDiv++;
		}
	}
}

function verif() 
{
	i = document.getElementById("type").value;
	CacheTout('calc');DivStatus('calc'+i,'');
}
verif();