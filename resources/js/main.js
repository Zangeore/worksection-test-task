import $ from 'jquery'
import Task from './components/task'
import TaskGroup from './components/task-group'
import './main.scss'

window.$ = window.jQuery = $;

const components = {
  "c-task": Task,
  'c-task-group': TaskGroup
}
Object.keys(components).forEach(name => {
  customElements.define(name, components[name])
});
