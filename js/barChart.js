const bCtx = $('#barChart')
new Chart(bCtx, {
    type:'line',
	data: {
      labels: ['貧乏神','浪費家', 'ノーマル', 'ちょっとしたすごいやつ', '金持ちコース'],
      datasets: [{
	 	label: '最高気温(度）',
	    data:{{echo json_encode($prices);}} ,
	    borderColor: "rgba(255,0,0,1)",
	    backgroundColor: "rgba(0,0,0,0,0,0)"
	    },
	  ],
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