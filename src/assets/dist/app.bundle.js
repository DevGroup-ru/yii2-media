/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId])
/******/ 			return installedModules[moduleId].exports;
/******/
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// identity function for calling harmony imports with the correct context
/******/ 	__webpack_require__.i = function(value) { return value; };
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 2);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Attachment = function () {
  function Attachment($input) {
    var _this = this;

    _classCallCheck(this, Attachment);

    this.$input = $input;
    this.$object = $input.closest('.media-attachment');

    this.settings = {
      dragOverClass: 'media-attachment__selected-files_drag_over',
      isUploadingClass: 'media-attachment__selected-files_is_uploading',
      isSuccessClass: 'media-attachment__selected-files_is_success',
      isErrorClass: 'media-attachment__selected-files_is_error'
    };

    this.uploadTarget = this.$input.data('uploadTarget');
    this.modelClassNameHash = this.$input.data('modelClassnameHash');
    this.modelId = this.$input.data('modelId');
    this.relationName = this.$input.data('relationName');

    this.csrfParam = this.$input.data('csrfParam');
    this.csrfToken = this.$input.data('csrfToken');

    this.$galleryContainer = this.$object.find('.media-attachment__gallery-container');

    this.$browseGallery = this.$object.find('.media-attachment__browse');
    this.$browseGallery.click(function () {
      _this.$galleryContainer.toggleClass('media-attachment__gallery-container_active');
    });

    this.$uploadButton = this.$object.find('.media-attachment__upload');

    this.$fakeInput = $('<input type="file" multiple>').attr('name', 'UploadModel[files][]');

    this.$uploadButton.click(function () {
      _this.$fakeInput.click();
      return false;
    });

    this.$selectedFiles = this.$object.find('.media-attachment__selected-files');

    this.droppedFiles = false;

    this.bindUpload();
  }

  _createClass(Attachment, [{
    key: 'bindUpload',
    value: function bindUpload() {
      var _this2 = this;

      this.$selectedFiles.on('drag dragstart dragend dragover dragenter dragleave drop', function (e) {
        // preventing the unwanted behaviours
        e.preventDefault();
        e.stopPropagation();
      }).on('dragover dragenter', function () {
        _this2.$selectedFiles.addClass(_this2.settings.dragOverClass);
      }).on('dragleave dragend drop', function () {
        _this2.$selectedFiles.removeClass(_this2.settings.dragOverClass);
      }).on('drop', function (e) {
        _this2.droppedFiles = e.originalEvent.dataTransfer.files; // the files that were dropped
        // trigger submit
        _this2.submit();
      });
      this.$fakeInput.change(function () {
        _this2.droppedFiles = _this2.$fakeInput[0].files;
        _this2.submit();
      });
    }
  }, {
    key: 'submit',
    value: function submit() {
      var _this3 = this;

      this.$selectedFiles.addClass(this.settings.isUploadingClass);
      var formData = new window.FormData();
      $.each(this.droppedFiles, function (i, file) {
        formData.append('UploadModel[files][]', file);
      });
      formData.append(this.csrfParam, this.csrfToken);
      formData.append('UploadModel[model_class_name_hash]', this.modelClassNameHash);
      formData.append('UploadModel[model_id]', this.modelId);
      formData.append('UploadModel[relation_name]', this.relationName);

      $.ajax({
        url: this.uploadTarget,
        method: 'POST',
        data: formData,
        dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        complete: function complete() {
          _this3.$selectedFiles.removeClass(_this3.settings.isUploadingClass);
          _this3.droppedFiles = false;
        },
        success: function success(data) {
          _this3.$selectedFiles.addClass(data.success === true ? _this3.settings.isSuccessClass : _this3.settings.isErrorClass);
          if (!data.success) {
            // $errorMsg.text(data.error);
          }
        }
      });
    }
  }], [{
    key: 'bindToInput',
    value: function bindToInput(input) {
      var $input = input instanceof $ ? input : $('#' + input);

      return new Attachment($input);
    }
  }]);

  return Attachment;
}();

exports.default = Attachment;

/***/ }),
/* 1 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 2 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


__webpack_require__(1);

var _Attachment = __webpack_require__(0);

var _Attachment2 = _interopRequireDefault(_Attachment);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

window.MediaAttachment = _Attachment2.default;

/***/ })
/******/ ]);
//# sourceMappingURL=app.bundle.js.map