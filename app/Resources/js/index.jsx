import autobind from 'autobind-decorator';
import ReactOnRails from 'react-on-rails';
import React from 'react';
import Timeline from './components/timeline.jsx';
import Header from './components/header.jsx';
import '../sass/common.scss';
import '../sass/index.scss';

@autobind
export default class RootComponent extends React.Component {

    constructor(props, context) {
        super(props, context);
        this.state = {
            today_timeline:     this.props.timeline_json,
            timeline_json:      this.props.timeline_json,
            json_daily_url:     this.props.json_daily_url,
            timeline_date_list: this.props.timeline_date_list,
            app_user_username:  this.props.app_user_username
        };
    }

    getDailyJson(date) {
        const newDate = new Date();
        const today = newDate.getFullYear() + '-' + (newDate.getMonth() + 1) + '-' + ('0' + newDate.getDate()).slice(-2);

        if (date === today) {
            this.setState({timeline_json: this.state.today_timeline});
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
