// map.js
import * as am5 from '@amcharts/amcharts5';
import * as am5map from '@amcharts/amcharts5/map';
import am5geodata_indonesiaLow from '@amcharts/amcharts5-geodata/indonesiaLow';
import am5themes_Animated from '@amcharts/amcharts5/themes/Animated';

export function initIndonesiaMap() {
  // Create root element
  const root = am5.Root.new('indonesia-map');

  // Set themes
  root.setThemes([am5themes_Animated.new(root)]);

  // Create chart
  const chart = root.container.children.push(
    am5map.MapChart.new(root, {
      panX: 'none',
      panY: 'none',
      projection: am5map.geoMercator(),
      paddingBottom: 20,
      paddingTop: 20,
      paddingLeft: 20,
      paddingRight: 20,
    })
  );

  // Create polygon series
  const polygonSeries = chart.series.push(
    am5map.MapPolygonSeries.new(root, {
      geoJSON: am5geodata_indonesiaLow,
      exclude: ['AQ'],
      fill: am5.color(0x6699cc),
      stroke: am5.color(0xffffff),
    })
  );

  // Configure series
  polygonSeries.mapPolygons.template.setAll({
    tooltipText: '{name}',
    interactive: true,
    fill: am5.color(0x6699cc),
  });

  // Hover state
  polygonSeries.mapPolygons.template.states.create('hover', {
    fill: am5.color(0xf59e0b),
  });

  // Click event
  polygonSeries.mapPolygons.template.events.on('click', function (ev) {
    const province = ev.target.dataItem?.dataContext;
    if (province) {
      console.log('Provinsi dipilih:', province.name);
      // You can add additional actions here
    }
  });

  // Add zoom control
  chart.set('zoomControl', am5map.ZoomControl.new(root, {}));

  // Hide loading indicator when map is ready
  document.querySelector('.loading-indicator').style.display = 'none';

  return { chart, polygonSeries };
}

export function populateProvinceList(polygonSeries) {
  // Sample data for popular provinces
  const popularProvinces = [
    { id: 'ID-JK', name: 'DKI Jakarta', umkmCount: 850 },
    { id: 'ID-JB', name: 'Jawa Barat', umkmCount: 1250 },
    { id: 'ID-JT', name: 'Jawa Tengah', umkmCount: 980 },
    { id: 'ID-JI', name: 'Jawa Timur', umkmCount: 1100 },
    { id: 'ID-BT', name: 'Banten', umkmCount: 620 },
  ];

  // Populate province list
  const provinceList = document.getElementById('province-list');
  popularProvinces.forEach((province) => {
    const item = document.createElement('div');
    item.className = 'province-item flex items-center p-3 bg-white rounded-lg shadow-sm cursor-pointer';
    item.innerHTML = `
            <div class="w-10 h-10 rounded-full bg-primary bg-opacity-10 text-primary flex items-center justify-center mr-4">
                <i class="fas fa-map-marker-alt"></i>
            </div>
            <div>
                <h4 class="font-bold">${province.name}</h4>
                <p class="text-sm text-gray-500">${province.umkmCount}+ UMKM Terdaftar</p>
            </div>
        `;

    item.addEventListener('click', () => {
      // Highlight the province on the map
      polygonSeries.mapPolygons.each((polygon) => {
        if (polygon.dataItem?.dataContext?.id === province.id) {
          polygonSeries.triggerHoverOnDataItem(polygon.dataItem);
          polygon.events.dispatch('click');
        }
      });
    });

    provinceList.appendChild(item);
  });
}
