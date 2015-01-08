/**
 * @preserve
 * flipsnap.js
 * 2012.3.14 update by zhoahe  
 *            Add position variable to config data 
 *            position = 'y'
 * 2012.3.21 update by zhoahe  
 *            Add position 'xy' for free move
 *
 * @version  0.2.2 
 * @url http://pxgrid.github.com/js-flipsnap/
 *
 * Copyright 2011 PixelGrid, Inc.
 * Licensed under the MIT License:
 * http://www.opensource.org/licenses/mit-license.php
 */

(function(window, document, undefined) {

var div = document.createElement('div');
var prefix = ['webkit', 'moz', 'o', 'ms'];
var saveProp = {};
var support = {};

support.transform3d = hasProp([
	'perspectiveProperty',
	'WebkitPerspective',
	'MozPerspective',
	'OPerspective',
	'msPerspective'
]);

support.transform = hasProp([
	'transformProperty',
	'WebkitTransform',
	'MozTransform',
	'OTransform',
	'msTransform'
]);

support.transition = hasProp([
	'transitionProperty',
	'WebkitTransitionProperty',
	'MozTransitionProperty',
	'OTransitionProperty',
	'msTransitionProperty'
]);

support.touch = 'ontouchstart' in window;

support.cssAnimation = (support.transform3d || support.transform) && support.transition;

var touchStartEvent = support.touch ? 'touchstart' : 'mousedown';
var touchMoveEvent = support.touch ? 'touchmove' : 'mousemove';
var touchEndEvent = support.touch ? 'touchend' : 'mouseup';

function Flipsnap(element, conf) {
	return (this instanceof Flipsnap)
		? this.init(element, conf)
		: new Flipsnap(element, conf);
}

Flipsnap.prototype.init = function(element, conf) {
	var self = this;

	self.conf = conf || {};
	self.currentPoint = 0;
	self.currentX = 0;
	self.currentY = 0;
	self.animation = false;
	self.scrollPosition = self.conf.position || 'x';
    self.endCallback = self.conf.callback || (function(){}) ;

	self.element = element;
	if (typeof element === 'string') {
		self.element = document.querySelector(element);
	}

	if (support.cssAnimation) {
		self._setStyle({
			transitionProperty: getCSSVal('transform'),
			transitionTimingFunction: 'cubic-bezier(0,0,0.25,1)',
			transitionDuration: '0ms',
			transform: getTranslate(0,self.scrollPosition,0)
		});
	}
	else {
		self._setStyle({
			position: 'relative',
			left: '0px'
		});
	}

	self.refresh();

	self.element.addEventListener(touchStartEvent, self, false);
	self.element.addEventListener(touchMoveEvent, self, false);
	self.element.addEventListener(touchEndEvent, self, false);

	return self;
};

Flipsnap.prototype.handleEvent = function(event) {
	var self = this;

	switch (event.type) {
		case touchStartEvent:
			self._touchStart(event);
			break;
		case touchMoveEvent:
			self._touchMove(event);
			break;
		case touchEndEvent:
			self._touchEnd(event);
			break;
		case 'click':
			self._click(event);
			break;
	}
};

Flipsnap.prototype.refresh = function() {
	var self = this;

	// setting max point
    // conf.point is backward compatibility. (deprecated)
	self.resetMax();
	self.moveToPoint();
};

Flipsnap.prototype.resetMax = function() {
	var self = this;
	self.maxPoint = self.conf.maxPoint || self.conf.point || (function() {
		var childNodes = self.element.childNodes,
			itemLength = 0,
			i = 0,
			len = childNodes.length,
			node;
		for(; i < len; i++) {
			node = childNodes[i];
			if (node.nodeType === 1) {
				itemLength++;
			}
		}
		if (itemLength > 0) {
			itemLength--;
		}

		return itemLength;
	})();

        // setting distance
	self.distance = self.conf.distance || (self.scrollPosition=='x'?self.element.scrollWidth:self.element.scrollHeight) / (self.maxPoint + 1);

	// setting maxX
	self.maxX = -self.distance * self.maxPoint;

	// setting maxX
	self.maxY = -self.distance * self.maxPoint;
}
Flipsnap.prototype.hasNext = function() {
	var self = this;

	return self.currentPoint < self.maxPoint;
};

Flipsnap.prototype.hasPrev = function() {
	var self = this;

	return self.currentPoint > 0;
};

Flipsnap.prototype.toNext = function() {
	var self = this;

	if (!self.hasNext()) {
		return;
	}

	self.moveToPoint(self.currentPoint + 1);
};

Flipsnap.prototype.toPrev = function() {
	var self = this;

	if (!self.hasPrev()) {
		return;
	}

	self.moveToPoint(self.currentPoint - 1);
};

Flipsnap.prototype.moveToPoint = function(point) {
	var self = this;

	var beforePoint = self.currentPoint;

	// not called from `refresh()`
	if (point === undefined) {
		point = self.currentPoint;
	}

	if (point < 0) {
		self.currentPoint = 0;
	}
	else if (point > self.maxPoint) {
		self.currentPoint = self.maxPoint;
	}
	else {
		self.currentPoint = parseInt(point);
	}

	if (support.cssAnimation) {
		self._setStyle({ transitionDuration: '350ms' });
	}
	else {
		self.animation = true;
	}
    if( self.scrollPosition=='x' ) {
        self._setX(- self.currentPoint * self.distance);
    }
    else if( self.scrollPosition=='y' ) {
        self._setY(- self.currentPoint * self.distance);
    }
	if (beforePoint !== self.currentPoint) { // is move?
		triggerEvent(self.element, 'fsmoveend', true, false);
		triggerEvent(self.element, 'flipsnap.moveend', true, false); // backward compatibility (deprecated)
	}
};

Flipsnap.prototype._setX = function(x) {
	var self = this;

	self.currentX = x;
	if (support.cssAnimation) {
		self.element.style[ saveProp.transform ] = getTranslate(x,self.scrollPosition);
	}
	else {/*
		if (self.animation) {
			self._animate(x);
		}
		else {
			if( self.scrollPosition=='x' )
				self.element.style.left = x + 'px';
			else 
				self.element.style.top = x + 'px';
		}*/
	}
};

Flipsnap.prototype._setY = function(y) {
	var self = this;

	self.currentY = y;
	self.element.style[ saveProp.transform ] = getTranslate(y,'y');
};

Flipsnap.prototype._setXY = function(x,y) {
	var self = this;

	self.currentX = x;
	self.currentY = y;
	self.element.style[ saveProp.transform ] = getTranslate(x,'xy',y);
};

Flipsnap.prototype._touchStart = function(event) {
	var self = this;

	if (self.conf.touchDisable) {
		return;
	}

	if (support.cssAnimation) {
		self._setStyle({ transitionDuration: '0ms' });
	}
	else {
		self.animation = false;
	}
	self.scrolling = true;
	self.moveReady = false;
	self.startPageX = getPage(event, 'pageX');
	self.startPageY = getPage(event, 'pageY');
	self.basePageX = self.startPageX;
	self.basePageY = self.startPageY;
	self.directionX = 0;
	self.directionY = 0;
	self.startTime = event.timeStamp;
};

Flipsnap.prototype._touchMove = function(event) {
	var self = this;

	if (!self.scrolling) {
		return;
	}

	var pageX = getPage(event, 'pageX'),
		pageY = getPage(event, 'pageY'),
		distX,
		distY,
		newX,
		newY,
		deltaX,
		deltaY;

	if (self.moveReady) {
		event.preventDefault();
		event.stopPropagation();

		distX = pageX - self.basePageX ;
		newX = self.currentX + distX;
		if (newX >= 0 || newX < self.maxX) {
			newX = Math.round(self.currentX + distX / 1);
		}
		distY = pageY - self.basePageY;
		newY = self.currentY + distY;
		if (newY >= 0 || newY < self.maxY) {
			newY = Math.round(self.currentY + distY / 1);
		}
		if( self.scrollPosition=='xy' ) {
            self._setXY(newX,newY);
        }
        else if( self.scrollPosition=='y' ) {
            self._setY(newY);
        }
        else {
            self._setX(newX);
        }
        /*
		distX = self.scrollPosition=='x'?(pageX - self.basePageX):(pageY - self.basePageY);
		newX = self.currentX + distX;
		if (newX >= 0 || newX < self.maxX) {
			newX = Math.round(self.currentX + distX / 3);
		}
		self._setX(newX);*/

		// When distX is 0, use one previous value.
		// For android firefox. When touchend fired, touchmove also
		// fired and distX is certainly set to 0. 
		self.directionX =
			distX === 0 ? self.directionX :
			distX > 0 ? -1 : 1;
		self.directionY =
			distY === 0 ? self.directionY :
			distY > 0 ? -1 : 1;
	}
	else {
		deltaX = Math.abs(pageX - self.startPageX);
		deltaY = Math.abs(pageY - self.startPageY);
		if (deltaX > 5) {
			event.preventDefault();
			event.stopPropagation();
			self.moveReady = true;
			self.element.addEventListener('click', self, true);
		}
		else if (deltaY > 5) {
			event.preventDefault();
			event.stopPropagation();
			self.moveReady = true;
			self.element.addEventListener('click', self, true);
			//self.scrolling = false;
		}
	}

	self.basePageX = pageX;
	self.basePageY = pageY;
};

Flipsnap.prototype._touchEnd = function(event) {
	var self = this;

	if (!self.scrolling) {
		return;
	}

	self.scrolling = false;

    if( self.scrollPosition=='x' ) {
        var newPoint = -self.currentX / self.distance;
        newPoint =
            (self.directionX > 0) ? Math.ceil(newPoint) :
            (self.directionX < 0) ? Math.floor(newPoint) :
            Math.round(newPoint);
        self.moveToPoint(newPoint);
    }
    else if(self.scrollPosition=='y') {
        var newPoint = -self.currentY / self.distance;
        newPoint =
            (self.directionY > 0) ? Math.ceil(newPoint) :
            (self.directionY < 0) ? Math.floor(newPoint) :
            Math.round(newPoint);
        self.moveToPoint(newPoint);
    }
    else {
        //if( self.currentX>self.maxX&&self.currentY<self.maxY ) {
            var newX = self.currentX+Math.round((self.basePageX-self.startPageX)/1);
            var newY = self.currentY+Math.round((self.basePageY-self.startPageY)/1);
            newX = self.directionX>0?Math.max( newX,-self.maxX ):Math.min( newX,0 );
            newY = self.directionY>0?Math.max( newY,-self.maxY ):Math.min( newY,0 );
            self._setStyle({ transitionDuration: '350ms' });
            self._setXY(newX,newY);
        //}
        console.log(self.currentX+','+self.maxX+';'+self.currentY+','+self.maxY+';'+self.directionX+','+self.directionY+';NewX:'+newX+',NexY:'+newY);
    }

	setTimeout(function() {
		self.element.removeEventListener('click', self, true);
	}, 200);

    self.endCallback();
};

Flipsnap.prototype._click = function(event) {
	var self = this;

	event.stopPropagation();
	event.preventDefault();
};

Flipsnap.prototype._setStyle = function(styles) {
	var self = this;
	var style = self.element.style;

	for (var prop in styles) {
		setStyle(style, prop, styles[prop]);
	}
};

Flipsnap.prototype._animate = function(x) {
    /*
	var self = this;

	var elem = self.element;
	var begin = +new Date;
	var from = parseInt(elem.style.left);
	var to = x;
	var duration = 350;
	var easing = function(time, duration) {
		return -(time /= duration) * (time - 2);
	};
	var timer = setInterval(function() {
		var time = new Date - begin;
		var pos, now;
		if (time > duration) {
			clearInterval(timer);
			now = to;
		}
		else {
			pos = easing(time, duration);
			now = pos * (to - from) + from;
		}
		if( self.scrollPosition=='x' )
			elem.style.left = now + 'px';
		else 
			elem.style.top = now + 'px';
		//elem.style.left = now + "px";
	}, 10);*/
};

Flipsnap.prototype.destroy = function() {
	var self = this;

	self.element.removeEventListener(touchStartEvent, self);
	self.element.removeEventListener(touchMoveEvent, self);
	self.element.removeEventListener(touchEndEvent, self);
};

function getTranslate(x,position,y) {
    var string = '' ;
    if( position=='x' ) 
        string = x+'px, 0, 0';
    else if( position=='y' ) 
        string = '0, '+x+'px, 0' ;
	else if( position=='xy' ) 
        string = x+'px,'+y+'px, 0';

	return support.transform3d
		? 'translate3d(' + string + ')'
		: 'translate(' + string + ')';
}

function getPage(event, page) {
	return support.touch ? event.changedTouches[0][page] : event[page];
}

function hasProp(props) {
	return some(props, function(prop) {
		return div.style[ prop ] !== undefined;
	});
}

function setStyle(style, prop, val) {
	var _saveProp = saveProp[ prop ];
	if (_saveProp) {
		style[ _saveProp ] = val;
	}
	else if (style[ prop ] !== undefined) {
		saveProp[ prop ] = prop;
		style[ prop ] = val;
	}
	else {
		some(prefix, function(_prefix) {
			var _prop = ucFirst(_prefix) + ucFirst(prop);
			if (style[ _prop ] !== undefined) {
				saveProp[ prop ] = _prop;
				style[ _prop ] = val;
				return true;
			}
		});
	}
}

function getCSSVal(prop) {
	if (div.style[ prop ] !== undefined) {
		return prop;
	}
	else {
		var ret;
		some(prefix, function(_prefix) {
			var _prop = ucFirst(_prefix) + ucFirst(prop);
			if (div.style[ _prop ] !== undefined) {
				ret = '-' + _prefix + '-' + prop;
				return true;
			}
		});
		return ret;
	}
}

function ucFirst(str) {
	return str.charAt(0).toUpperCase() + str.substr(1);
}

function some(ary, callback) {
	for (var i = 0, len = ary.length; i < len; i++) {
		if (callback(ary[i], i)) {
			return true;
		}
	}
	return false;
}

function triggerEvent(element, type, bubbles, cancelable) {
	var ev = document.createEvent('Event');
	ev.initEvent(type, bubbles, cancelable);
	element.dispatchEvent(ev);
}

window.Flipsnap = Flipsnap;

})(window, window.document);
