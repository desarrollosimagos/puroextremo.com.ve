/**
 * Adobe Edge: symbol definitions
 */
(function($, Edge, compId){
//images folder
var im='images/';

var fonts = {};


var resources = [
];
var symbols = {
"stage": {
   version: "2.0.0",
   minimumCompatibleVersion: "2.0.0",
   build: "2.0.0.250",
   baseState: "Base State",
   initialState: "Base State",
   gpuAccelerate: false,
   resizeInstances: false,
   content: {
         dom: [
         {
            id:'icono-deportes-01',
            type:'image',
            rect:['93px','0','107px','96px','auto','auto'],
            fill:["rgba(0,0,0,0)",im+"icono-deportes-01.svg",'0px','0px']
         },
         {
            id:'icono-deportes-02',
            type:'image',
            rect:['200px','0','107px','96px','auto','auto'],
            fill:["rgba(0,0,0,0)",im+"icono-deportes-02.svg",'0px','0px']
         },
         {
            id:'icono-deportes-03',
            type:'image',
            rect:['0px','0','107px','96px','auto','auto'],
            fill:["rgba(0,0,0,0)",im+"icono-deportes-03.svg",'0px','0px']
         }],
         symbolInstances: [

         ]
      },
   states: {
      "Base State": {
         "${_Stage}": [
            ["color", "background-color", 'rgba(166,166,166,1.00)'],
            ["style", "width", '300px'],
            ["style", "height", '100px'],
            ["style", "overflow", 'hidden']
         ],
         "${_icono-deportes-02}": [
            ["style", "top", '0px'],
            ["style", "opacity", '0'],
            ["style", "left", '-108px']
         ],
         "${_icono-deportes-01}": [
            ["style", "top", '0px'],
            ["style", "opacity", '0'],
            ["style", "left", '-106px']
         ],
         "${_icono-deportes-03}": [
            ["style", "top", '0px'],
            ["style", "height", '96px'],
            ["style", "opacity", '0'],
            ["style", "left", '-109px'],
            ["style", "width", '107px']
         ]
      }
   },
   timelines: {
      "Default Timeline": {
         fromState: "Base State",
         toState: "",
         duration: 4620,
         autoPlay: true,
         timeline: [
            { id: "eid32", tween: [ "style", "${_icono-deportes-03}", "opacity", '1', { fromValue: '0'}], position: 0, duration: 950, easing: "easeInExpo" },
            { id: "eid25", tween: [ "style", "${_icono-deportes-03}", "opacity", '0', { fromValue: '1'}], position: 950, duration: 1010, easing: "easeInExpo" },
            { id: "eid12", tween: [ "style", "${_icono-deportes-02}", "top", '0px', { fromValue: '0px'}], position: 950, duration: 0 },
            { id: "eid24", tween: [ "style", "${_icono-deportes-02}", "left", '200px', { fromValue: '-108px'}], position: 1500, duration: 917, easing: "easeInExpo" },
            { id: "eid33", tween: [ "style", "${_icono-deportes-02}", "opacity", '1', { fromValue: '0'}], position: 1500, duration: 917, easing: "easeInExpo" },
            { id: "eid27", tween: [ "style", "${_icono-deportes-02}", "opacity", '0', { fromValue: '1'}], position: 2417, duration: 833, easing: "easeInExpo" },
            { id: "eid34", tween: [ "style", "${_icono-deportes-01}", "opacity", '1', { fromValue: '0'}], position: 2840, duration: 603, easing: "easeInExpo" },
            { id: "eid29", tween: [ "style", "${_icono-deportes-01}", "opacity", '0', { fromValue: '1'}], position: 3443, duration: 1177, easing: "easeInExpo" },
            { id: "eid5", tween: [ "style", "${_icono-deportes-03}", "height", '96px', { fromValue: '96px'}], position: 0, duration: 0 },
            { id: "eid10", tween: [ "style", "${_icono-deportes-03}", "left", '189px', { fromValue: '-109px'}], position: 0, duration: 950, easing: "easeInExpo" },
            { id: "eid14", tween: [ "style", "${_icono-deportes-01}", "top", '0px', { fromValue: '0px'}], position: 0, duration: 0 },
            { id: "eid21", tween: [ "style", "${_icono-deportes-01}", "top", '0px', { fromValue: '0px'}], position: 950, duration: 0 },
            { id: "eid23", tween: [ "style", "${_icono-deportes-01}", "left", '191px', { fromValue: '-106px'}], position: 2840, duration: 931, easing: "easeInExpo" },
            { id: "eid3", tween: [ "style", "${_icono-deportes-03}", "top", '0px', { fromValue: '0px'}], position: 0, duration: 0 },
            { id: "eid7", tween: [ "style", "${_icono-deportes-03}", "width", '107px', { fromValue: '107px'}], position: 0, duration: 0 }         ]
      }
   }
}
};


Edge.registerCompositionDefn(compId, symbols, fonts, resources);

/**
 * Adobe Edge DOM Ready Event Handler
 */
$(window).ready(function() {
     Edge.launchComposition(compId);
});
})(jQuery, AdobeEdge, "EDGE-27094677");
