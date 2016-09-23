/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};

/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {

/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId])
/******/ 			return installedModules[moduleId].exports;

/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};

/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);

/******/ 		// Flag the module as loaded
/******/ 		module.l = true;

/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}


/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;

/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;

/******/ 	// identity function for calling harmory imports with the correct context
/******/ 	__webpack_require__.i = function(value) { return value; };

/******/ 	// define getter function for harmory exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		Object.defineProperty(exports, name, {
/******/ 			configurable: false,
/******/ 			enumerable: true,
/******/ 			get: getter
/******/ 		});
/******/ 	};

/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};

/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };

/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";

/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ function(module, exports) {

eval("var showHideSwitches = document.querySelectorAll('[data-show-hide]');\n\nfor (var i = 0; i < showHideSwitches.length; i++) {\n    showHideSwitches[i].addEventListener('click', function (e) {\n        e.preventDefault();\n        var arrow = this.getElementsByTagName('i')[0];\n        var showHideElement = document.getElementById(this.dataset.showHide);\n\n        if (arrow.className.indexOf('down') > 0) {\n            arrow.className = arrow.className.replace('down', 'up');\n            showHideElement.className = showHideElement.className.replace('hide', '');\n        } else {\n            arrow.className = arrow.className.replace('up', 'down');\n            showHideElement.className = showHideElement.className + ' hide';\n        }\n    });\n}\n\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiMC5qcyIsInNvdXJjZXMiOlsid2VicGFjazovLy9yZXNvdXJjZXMvYXNzZXRzL2pzL2FwcC5qcz84YjY3Il0sInNvdXJjZXNDb250ZW50IjpbInZhciBzaG93SGlkZVN3aXRjaGVzID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbCgnW2RhdGEtc2hvdy1oaWRlXScpO1xuXG5mb3IgKHZhciBpID0gMDsgaSA8IHNob3dIaWRlU3dpdGNoZXMubGVuZ3RoOyBpKyspIHtcbiAgICBzaG93SGlkZVN3aXRjaGVzW2ldLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgZnVuY3Rpb24gKGUpIHtcbiAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICB2YXIgYXJyb3cgPSB0aGlzLmdldEVsZW1lbnRzQnlUYWdOYW1lKCdpJylbMF07XG4gICAgICAgIHZhciBzaG93SGlkZUVsZW1lbnQgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCh0aGlzLmRhdGFzZXQuc2hvd0hpZGUpO1xuXG4gICAgICAgIGlmIChhcnJvdy5jbGFzc05hbWUuaW5kZXhPZignZG93bicpID4gMCkge1xuICAgICAgICAgICAgYXJyb3cuY2xhc3NOYW1lID0gYXJyb3cuY2xhc3NOYW1lLnJlcGxhY2UoJ2Rvd24nLCAndXAnKTtcbiAgICAgICAgICAgIHNob3dIaWRlRWxlbWVudC5jbGFzc05hbWUgPSBzaG93SGlkZUVsZW1lbnQuY2xhc3NOYW1lLnJlcGxhY2UoJ2hpZGUnLCAnJyk7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICBhcnJvdy5jbGFzc05hbWUgPSBhcnJvdy5jbGFzc05hbWUucmVwbGFjZSgndXAnLCAnZG93bicpO1xuICAgICAgICAgICAgc2hvd0hpZGVFbGVtZW50LmNsYXNzTmFtZSA9IHNob3dIaWRlRWxlbWVudC5jbGFzc05hbWUgKyAnIGhpZGUnO1xuICAgICAgICB9XG4gICAgfSk7XG59XG5cblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyByZXNvdXJjZXMvYXNzZXRzL2pzL2FwcC5qcyJdLCJtYXBwaW5ncyI6IkFBQUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOyIsInNvdXJjZVJvb3QiOiIifQ==");

/***/ }
/******/ ]);