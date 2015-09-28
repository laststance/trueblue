var React = require('react');
var $ = require('jquery');
var Timeline = require('./timeline.jsx');
var Menu = require('./menu.jsx');

var TimelineComponent = React.createClass({
  getInitialState() {
    return {
      timeline_json: timeline_json,
      json_daily_url: json_daily_url,
      timeline_date_list: timeline_date_list
    };
  },
  getDailyJson(date) {
    $.get(json_daily_url + '/' + date, function(json) {
      this.setState({timeline_json: json});
    }.bind(this));
  },
  render() {
    return (
      <div>
        <Timeline timeline_json={this.state.timeline_json} />
        <Menu onClick={this.getDailyJson} timeline_date_list={this.state.timeline_date_list} />
      </div>
    );
  }
});

React.render(
  <TimelineComponent />,
  document.getElementById('container')
);
