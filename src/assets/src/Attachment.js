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

    this.fs = {
      uploadTarget: this.$input.data('fsUploadTarget'),
      listFiles: this.$input.data('fsListFiles'),
      listFolders: this.$input.data('fsListFolders'),
      trees: this.$input.data('fsTrees'),
      getFiles: this.$input.data('fsGetFiles')
    };

    this.model = this.$input.data('model');
    this.modelId = this.$input.data('modelId');
    this.relationName = this.$input.data('relationName');

    this.csrfParam = this.$input.data('csrfParam');
    this.csrfToken = this.$input.data('csrfToken');

    this.$galleryContainer = this.$object.find('.media-attachment__gallery-container');

    this.$browseGallery = this.$object.find('.media-attachment__browse');
    this.$browseGallery.click(() => {
      this.$galleryContainer.toggleClass('media-attachment__gallery-container_active');
      return false;
    });

    this.$uploadButton = this.$object.find('.media-attachment__upload');

    this.$fakeInput = $('<input type="file" multiple>')
      .attr('name', 'UploadModel[files][]');

    this.$uploadButton.click(() => {
      this.$fakeInput.click();
      return false;
    });


    this.$selectedFiles = this.$object.find('.media-attachment__selected-files');
    const that = this;
    this.$selectedFiles.on('click', '.media-attachment__file-delete', function() {
      that.removeFile($(this).closest('.media-attachment__file'));
      return false;
    });

    this.droppedFiles = false;

    this.bindUpload();

    this.$selectedFiles.sortable({
      handle: '.media-attachment__file-thumb',
      items: '.media-attachment__file',
      forcePlaceholderSize: true,
      placeholder: 'media-attachment__file',
      opacity: 0.5,
      update: () =>
        this.$input.val(
          this.$selectedFiles
            .sortable(
              'toArray',
              {attribute: 'data-id'}
            ).join(',')
        )
    });

    this.refreshFiles();
  }

  bindUpload() {
    this.$selectedFiles
      .on('drag dragstart dragend dragover dragenter dragleave drop', function (e) {
        // preventing the unwanted behaviours
        if (e.originalEvent.dataTransfer.files) {
          e.preventDefault();
          e.stopPropagation();
        }
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
      url: this.fs.uploadTarget,
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
        } else {
          let values = this.$input.val();
          if (values !== '') {
            values += ',';
          }
          values += data.ids;
          values = values.split(',').filter(function onlyUnique(value, index, self) {
            return self.indexOf(value) === index;
          }).join(',');
          this.$input.val(values);

          this.refreshFiles();
        }
      }
    });
  }

  refreshFiles() {
    const ids = this.$input.val();
    if (ids === '') {
      return;
    }

    this.$selectedFiles.addClass(this.settings.isUploadingClass);
    const data = {ids};
    data[this.csrfParam] = this.csrfToken;
    $.ajax({
      url: this.fs.getFiles,
      data,
      method: 'POST',
      cache: false,
      complete: () => {
        this.$selectedFiles.removeClass(this.settings.isUploadingClass);
      },
      success: (data) => {
        this.$selectedFiles.empty();
        const orderedIds = ids.split(',');
        const allFiles = [];

        orderedIds.forEach((id) => {
          if (data[id]) {
            allFiles.push(Attachment.$file(data[id]));
          }
        });

        this.$selectedFiles.append(allFiles);
      }
    });
  }

  static $file(decl) {
    let thumb = '';
    if (decl.imageData) {
      if (decl.imageData.thumb) {
        thumb = `<img src="${decl.imageData.thumb.fileData.public_url}">`;
      }
    }

    return $(`
<div class="media-attachment__file" data-id="${decl.id}">
    <div class="media-attachment__file-thumb">
        ${thumb}
    </div>
    <div class="media-attachment__file-name">
        ${decl.name}        
    </div>
    <a href="#" class="media-attachment__file-delete">
        <i class="fa fa-trash-o fa-fw"></i>    
    </a>
</div>
`);
  }

  removeFile($file) {
    const id = $file.data('id');
    const vals = this.$input.val().split(',');
    vals.splice(vals.indexOf(`${id}`), 1);
    this.$input.val(vals.join(','));
    $file.remove();
  }

  static bindToInput(input) {
    const $input = input instanceof $ ? input : $(`#${input}`);

    return new Attachment($input);
  }
}

export default Attachment;