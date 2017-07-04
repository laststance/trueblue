import autobind from 'autobind-decorator'
import React from 'react'
import {connect} from 'react-redux'
import ReactCSSTransitionGroup from 'react-addons-css-transition-group'
import Lightbox from 'react-images'
import {isSP} from '../utils/util'
import Slider from 'ryotamurakami-react-slick'
import Actions from '../actions/home'

@autobind
class Timeline extends React.Component {
    constructor(props) {
        super(props)
        this.state = {
            currentIndex: this.props.currentIndex
        }
    }

    componentWillReceiveProps(props) {
        this.setState({currentIndex: props.currentIndex})
    }

    render() {
        return this.renderAlldaysSlider(this.props.timelineJson)
    }

    renderAlldaysSlider(timeline) {
        const alldaysTimeline = timeline.map((oneday) => {
            const prop = Object.keys(oneday);
            const onedayContents = oneday[prop];
                if (onedayContents.error) {
                    return (
                        <div id="timeline" className="row">
                            <div style={{margin: 0}}>
                                <section
                                    className='timeline-item error-msg col-lg-offset-2 col-lg-8 col-md-offset-1 col-md-10 col-sm-offset-1 col-sm-10 col-xs-offset-1 col-xs-10'>
                                    <p>tweet not found.</p>
                                </section>
                            </div>
                        </div>
                    )
                }

            const onedayTimelines = onedayContents.map(this.renderOnedaySlider);

            return (
                <section>
                    <ReactCSSTransitionGroup
                        transitionName="timeline"
                        transitionEnterTimeout={500}
                        transitionLeaveTimeout={500}
                        id="timeline"
                        className="row"
                    >
                        {onedayTimelines}
                    </ReactCSSTransitionGroup>
                </section>
            )
        })

        const settings = {
            arrows: false, // disable prev|next ui button
            initialSlide: this.state.currentIndex, // rows displayed at initial loading
            slickGoTo: this.state.currentIndex, // reactive changed store.currentIndex at other Action (etc. calender UI)
            afterChange: (currentSlideIntegerNumber) => { // when swipe row, update store.currentIndex & store.currentDate
                this.props.setCurrentIndex(currentSlideIntegerNumber)
                const ymdString = Object.keys(this.props.timelineJson[currentSlideIntegerNumber]).toString()
                this.props.setCurrentDate(ymdString)
            },
            lazyLoad: true
        }

        return (
            <Slider ref='slider' {...settings}>
                {alldaysTimeline}
            </Slider>
        )
    }

    renderOnedaySlider(tweet, index) {
        const date = new Date(tweet.created_at)
        return (
            <div className="col-lg-offset-1 col-lg-10" key={tweet.id_str}>
                <div className="row">
                    <section
                        className='timeline-item col-lg-offset-2 col-lg-8 col-md-offset-1 col-md-10 col-sm-offset-1 col-sm-10 col-xs-offset-1 col-xs-10'
                        data-id={tweet.id_str} data-index={index}>
                        <div className="contents">
                            <div className="left-side">
                                <img className="profile-image" src={tweet.user.profile_image_url}/>
                            </div>
                            <div className="right-side">
                                <span className="user-name">{tweet.user.name}</span>
                                <span className="screen-name">@{tweet.user.screen_name}</span>
                                <p className="text" dangerouslySetInnerHTML={{__html: tweet.text}}></p>
                                {(() => { // TODO mediaコンポーネントに切り出す
                                    // URLのtwitter card的なものを表示
                                    let urls = tweet.entities.urls
                                    if (urls.length > 0) {
                                        const url = 'https://hatenablog-parts.com/embed?url=' + urls[urls.length - 1].url
                                        return <p style={{margin: '10px 0'}}>
                                            <iframe src={url} className="urlcard" scrolling="no" frameBorder="0" style={{width: '100%', height: '155px', maxWidth: '500px'}}></iframe>
                                        </p>
                                    }

                                    let media = [] // 画像や動画が添付されていないtweetではtweet.entities.mediaがundefinedになる
                                    if (typeof tweet.entities.media !== 'undefined') media = tweet.entities.media
                                    if (media.length > 0) {
                                        // 画像URL → imgタグ
                                        let images = []
                                        for (var i = 0; i < media.length; i++) {
                                            if (media[i].type == 'photo') { // TODO photo以外のメディアを表示する方法を考える
                                                images.push({'src': media[i].media_url_https})
                                            }
                                        }
                                        if (images.length > 0) {
                                            const imgtags = images.map((i) => {
                                                return <img src={i.src} style={{width: '100%'}} key={i.id}/>
                                            })
                                            const h = isSP() ? '300px' : '400px'
                                            return <section style={{
                                                overflow: 'hidden',
                                                height: h,
                                                position: 'relative',
                                                margin: '10px 0'
                                            }}>
                                                {imgtags}
                                                {/*<Lightbox*/}
                                                {/*images={images}*/}
                                                {/*/>*/}
                                            </section>
                                        }
                                    }
                                })()}
                                <p className="create-at">{date.getFullYear()}年{date.getMonth() + 1}月{date.getDate()}日 {date.getHours()}時{date.getMinutes()}分</p>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        )
    }
}

const mapStateToProps = (state) => (
    {
        timelineJson: state.homeState.timelineJson,
        timelineDateList: state.homeState.timelineDateList,
        currentDate: state.homeState.currentDate,
        currentIndex: state.homeState.currentIndex,
        username: state.homeState.username,
    }
)

function mapDispatchToProps(dispatch) {
    return {
        fetchSingleDate: (username, date) => {
            dispatch(Actions.fetchSingleDate(username, date))
        },
        setCurrentIndex: (i) => {
            dispatch(Actions.setCurrentIndex(i))
        }, setCurrentDate: (ymdString) => {
            dispatch(Actions.setCurrentDate(ymdString))
        }
    }
}

export default connect(mapStateToProps, mapDispatchToProps)(Timeline)
