import './task-group.scss';

class TaskGroup extends HTMLElement {
  connectedCallback() {
    this.bindGlobalListeners();
  }

  bindGlobalListeners() {
    this.addEventListener('task-submit', (e) => {
      const { value, newTask, id, status } = e.detail;

      if (newTask) {
        this.request('POST', '/todo', { task: value, status });
      } else {
        this.request('PUT', `/todo/${id}`, { task: value, status });
      }
    });

    this.addEventListener('task-remove', (e) => {
      const { id } = e.detail;
      this.request('DELETE', `/todo/${id}`);
    });
  }

  request(method, url, data = {}) {
    $.ajax(url, {
      method,
      data,
      success: (response) => {
        this.renderTasks(response.tasks);
      },
      error: (xhr) => {
        if (xhr.status === 422) {
          const errors = xhr.responseJSON.errors;
          const lines = Object.entries(errors).map(([field, messages]) => `${field}: ${messages.join(', ')}`);
          alert(`Validation failed:\n${lines.join('\n')}`);
        } else {
          alert('Something went wrong');
        }
      }
    });
  }

  renderTasks(tasks = []) {
    this.innerHTML = '';

    for (const task of tasks) {
      const el = document.createElement('c-task');
      el.setAttribute('id', task.id);
      el.setAttribute('value', task.task);
      el.setAttribute('status', task.status);
      this.appendChild(el);
    }

    const newTask = document.createElement('c-task');
    newTask.setAttribute('placeholder', 'Enter task name ...');
    this.appendChild(newTask);
  }

}

export default TaskGroup;
