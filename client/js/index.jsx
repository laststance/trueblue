import ReactOnRails from 'react-on-rails';
const React = require('react');
const Timeline = require('./components/timeline.jsx');
const Header = require('./components/header.jsx');
require('../sass/main.scss');
require('../sass/index.scss');

export default class RootComponent extends React.Component {

    constructor(props, context) {
        super(props, context);
        this.state = {
            timeline_json:      this.props.timeline_json,
            json_daily_url:     this.props.json_daily_url,
            timeline_date_list: this.props.timeline_date_list,
            app_user_username:  this.props.app_user_username
        };
    }

    getDailyJson(date) {
        const newDate = new Date();
        const today = newDate.getFullYear() + '-0' + (newDate.getMonth() + 1) + '-' + newDate.getDate();

        if (date === today) {
            this.setState({timeline_json: timeline_json});
        } else {
            $.get(this.state.json_daily_url + '/' + date, ((json)=> {
                this.setState({timeline_json: json});
            }).bind(this));
        }
        return 0;
    }

    render() {
        return (
            <div>
                <Header getDailyJson={this.getDailyJson.bind(this)} timeline_date_list={this.state.timeline_date_list}
                        app_user_username={this.state.app_user_username}/>
                <Timeline timeline_json={this.state.timeline_json}/>
            </div>
        );
    }
}

ReactOnRails.register({RootComponent});
