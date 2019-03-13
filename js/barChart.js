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
	    type:'bar',
		data: {
	      labels: ['初心者','素人', '普通の人', '達人', '超人'],
	          datasets: [{
		 	label: '節約レベル人数',
		    data: [choujin,tetujin,futu,shiroto,syoshinsya],
		    backgroundColor:[
	            '#f89939',
	            '#f7872d',
	            '#f76b1c',
	            'RGB(255,102,0)',
		    	'#ff3300',
	            ]
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