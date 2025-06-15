import './task.scss';
import $ from 'jquery';

class Task extends HTMLElement {
  constructor() {
    super();
  }

  static get observedAttributes() {
    return ['id', 'value', 'status', 'placeholder'];
  }

  attributeChangedCallback(name, oldValue, newValue) {
    if (oldValue === newValue) return;

    switch (name) {
      case 'value':
        this._value = newValue;
        if (this.$input) this.$input.val(this._value);
        break;
      case 'status':
        this._status = newValue;
        if (this.$checkbox) this.$checkbox.prop('checked', this._status === 'done');
        break;
      case 'placeholder':
        if (this.$input) this.$input.attr('placeholder', newValue);
        break;
      case 'id':
        this._id = newValue;
        this._newTask = !newValue;
        break;
    }
  }


  connectedCallback() {
    this.render();
    this.bindEvents();
    this._id = this.getAttribute('id') || null;
    this._value = this.getAttribute('value') || '';
    this._defaultValue = this._value;
    this._status = this.getAttribute('status') || 'undone';
    this._defaultStatus = this._status;
    this._newTask = !this._id;
  }

  render() {
    this.innerHTML = `
      <input ${this._id ? '' : 'hidden'} type="checkbox" class="c-checkbox" ${this._status === 'done' ? 'checked' : ''} />
      <input type="text" class="c-input" placeholder="${this.getAttribute('placeholder') || ''}" />
      <div style="display: none" class="focus-group">
        <span class="cancel">&times;</span>
        <span class="submit">OK <img alt="enter" src="/img/enter.svg" width="10" height="10"/></span>
      </div>
      <div style="display: none" class="hover-group">
        <span class="delete"><img src="/img/trash.svg" width="10" height="12" alt="trash"/></span>
      </div>
    `;

    this.$el = $(this);
    this.$input = this.$el.find('input[type="text"]');
    this.$checkbox = this.$el.find('input[type="checkbox"]');
    this.$focusGroup = this.$el.find('.focus-group');
    this.$hoverGroup = this.$el.find('.hover-group');

    this.$input.val(this._value);
    this.$checkbox.prop('checked', this._status === 'done');
  }

  bindEvents() {
    this.$input.on('input', (e) => {
      this._value = e.target.value;
    });

    this.$checkbox.on('change', (e) => {
      this._status = e.target.checked ? 'done' : 'undone';
      this.submit();
    });

    this.$input.on('focus', () => {
      this.$hoverGroup.hide();
      this.$focusGroup.show();
      this.classList.add('focus');

      $(document).on('mousedown.task-blur', (e) => {
        if (!this.contains(e.target)) {
          this.escape();
          $(document).off('mousedown.task-blur');
        }
      });
    });

    this.$input.on('keydown', (e) => {
      if (e.key === 'Enter') {
        e.preventDefault();
        this.submit();
      } else if (e.key === 'Escape') {
        e.preventDefault();
        this.escape();
      }
    });

    this.$focusGroup.find('.cancel').on('click', () => this.escape());
    this.$focusGroup.find('.submit').on('click', () => this.submit());

    this.$el.on('mouseenter', () => {
      if (!this.classList.contains('focus') && this._id) {
        this.$hoverGroup.show();
      }
    });

    this.$el.on('mouseleave', () => this.$hoverGroup.hide());

    this.$hoverGroup.find('.delete').on('click', (e) => {
      e.stopPropagation();
      this.dispatchEvent(new CustomEvent('task-remove', {
        bubbles: true,
        composed: true,
        detail: {id: this._id}
      }));
    });
  }

  submit() {
    this._defaultValue = this._value;
    this._defaultStatus = this._status;
    this.classList.remove('focus');
    this.$focusGroup.hide();

    this.dispatchEvent(new CustomEvent('task-submit', {
      bubbles: true,
      composed: true,
      detail: {
        id: this._id,
        newTask: this._newTask,
        value: this._value,
        status: this._status,
      },
    }));
  }

  escape() {
    this._value = this._defaultValue;
    this._status = this._defaultStatus;

    this.$input.val(this._value);
    this.$checkbox.prop('checked', this._status === 'done');

    this.classList.remove('focus');
    this.$focusGroup.hide();
    this.$input.blur();
  }
}

export default Task;
