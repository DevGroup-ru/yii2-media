class Attachment {
  constructor($input) {
    this.$input = $input;
    this.$object = $input.closest('.media-attachment');

    this.settings = {
      dragOverClass: 'media-attachment__selected-files_drag_over',
      isUploadingClass: 'media-attachment__selected-files_is_uploading',
      isSuccessClass: 'media-attachment__selected-files_is_success',
      isErrorClass: 'media-attachment__selected-files_is_error'
    };

    this.uploadTarget = this.$input.data('uploadTarget');
    this.model = this.$input.data('model');
    this.modelId = this.$input.data('modelId');
    this.relationName = this.$input.data('relationName');

    this.csrfParam = this.$input.data('csrfParam');
    this.csrfToken = this.$input.data('csrfToken');

    this.$galleryContainer = this.$object.find('.media-attachment__gallery-container');

    this.$browseGallery = this.$object.find('.media-attachment__browse');
    this.$browseGallery.click(() => {
      this.$galleryContainer.toggleClass('media-attachment__gallery-container_active');
    });

    this.$uploadButton = this.$object.find('.media-attachment__upload');

    this.$fakeInput = $('<input type="file" multiple>')
      .attr('name', 'UploadModel[files][]');

    this.$uploadButton.click(() => {
      this.$fakeInput.click();
      return false;
    });


    this.$selectedFiles = this.$object.find('.media-attachment__selected-files');

    this.droppedFiles = false;

    this.bindUpload();
  }

  bindUpload() {
    this.$selectedFiles
      .on('drag dragstart dragend dragover dragenter dragleave drop', function (e) {
        // preventing the unwanted behaviours
        e.preventDefault();
        e.stopPropagation();
      })
      .on('dragover dragenter', () => {
        this.$selectedFiles.addClass(this.settings.dragOverClass);
      })
      .on('dragleave dragend drop', () => {
        this.$selectedFiles.removeClass(this.settings.dragOverClass);
      })
      .on('drop', (e) => {
        this.droppedFiles = e.originalEvent.dataTransfer.files; // the files that were dropped
        // trigger submit
        this.submit();
      });
    this.$fakeInput.change(() => {
      this.droppedFiles = this.$fakeInput[0].files;
      this.submit();
    });
  }

  submit() {
    this.$selectedFiles.addClass(this.settings.isUploadingClass);
    const formData = new window.FormData();
    $.each(this.droppedFiles, function(i, file) {
      formData.append('UploadModel[files][]', file);
    });
    formData.append(this.csrfParam, this.csrfToken);
    formData.append('UploadModel[model]', this.model);
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
      complete: () => {
        this.$selectedFiles.removeClass(this.settings.isUploadingClass);
        this.droppedFiles = false;
      },
      success: (data) => {
        this.$selectedFiles
          .addClass(data.success === true ? this.settings.isSuccessClass : this.settings.isErrorClass);
        if (!data.success) {
          // $errorMsg.text(data.error);
        }
      }
    });
  }

  static bindToInput(input) {
    const $input = input instanceof $ ? input : $(`#${input}`);

    return new Attachment($input);
  }
}

export default Attachment;