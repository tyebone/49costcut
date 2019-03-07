const cCtx = $('#barChart')
function showChart(price_sum) {
	let choujin = 0;
	let tetujin = 0;
	let futu = 0;
	let shiroto = 0;
	let syoshinsya = 0;

	price_sum.forEach (function(val){
		if (val < 15000) {
	      choujin ++
	    }else if (val < 22500) {
	      tetujin ++
	    }else if (val < 30000) {
	      futu ++
	    }else if (val < 45000) {
	      shiroto ++
	    }else{
	      syoshinsya ++
	    }
	    })
	new Chart(cCtx, {
	    type:'line',
		data: {
	      labels: ['初心者','素人', '普通の人', '達人', '超人'],
	          datasets: [{
		 	label: '最高気温(度）',
		    data: [choujin,tetujin,futu,shiroto,syoshinsya],
		    borderColor: "rgba(255,0,0,1)",
		    backgroundColor: "rgba(0,0,0,0,0,0)"
		    }],
	},
		options: {
			scales: {
				yAxes: [{
					ticks: {

						//グラフの最小値を0にする
						beginAtZero: true
					}
				}]
			}
		}
	})
}