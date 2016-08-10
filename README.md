# Fractabl Boxes Image Generator

A simple PHP script that generates an image of randomly generated boxes using fractals.

## How to use

1. Host the script somewhere
2. link to the script, yes the php file, as an image. It will respond as an image, not HTML as you might normally expect.

## URL Parameters

To change the image manipulate it via url parms:

* `width`: width of image, in pixels
* `height`: height of image, in pixels
* `colors[]`: array of hex colors.
* `shading`: "lighter", "darker" or "both". Ligter makes the bloxes colors lighter than the chosen color, darker makes them darker. default is "both".
* `fractals`: how many steps should be used to build the fractal. defaults to 4.
* `from`: which side the fractal boxes should go "up" from, think mountain range base. Can be "left", "right", "top" or "bottom" (default).
* `transparents`: chance [0.0, 1.0] that a box won't be drawn, thus making "holes" in the image. Looks cool.
* `steps`: How many steps away from the chosen colors to step (direction depends on shading, amount is stepping).
* `stepping`: Percentage each step should take

Ex.

colors[] = FFFFFF
steps = 5
stepping = 0.25
shading = "lighter"

results in colors being:
1. RGB(255, 255, 255) <- original color is first step
2. RGB(192, 192, 192)
3. RGB(128, 128, 128)
4. RGB(64, 64, 64)
5. RGB(0, 0, 0)

The four steps are 25% darker.

## Demo

![demo green](http://fractalboxes.jacobfischer.me/?width=1000&height=270&transparents=0.30&from=top&colors[]=47CD32)

![demo merica](http://fractalboxes.jacobfischer.me/?width=1000&height=270&colors[]=CD0000&colors[]=CDCDCD&colors[]=0000DC&from=left&steps=2)

![demo gold silver](http://fractalboxes.jacobfischer.me/?width=400&height=300&from=right&colors[]=000000&colors[]=FFDF00&colors[]=C0C0C0&steps=3)

![demo orange](http://fractalboxes.jacobfischer.me/?width=600&height=300&transparents=0.15&from=bottom&colors[]=CD6700&steps=5)

![demo RGB](http://fractalboxes.jacobfischer.me/?width=400&height=300&from=right&colors[]=FF0000&colors[]=00FF00&colors[]=0000FF&shading=darker)

![demo transparents](http://fractalboxes.jacobfischer.me/?width=300&height=400&from=top&colors[]=551A8B&transparents=00.66)

## Other Notes

Please note this is an old script I found that I make back in 2010ish. I promise no support, and don't judge me too harshly. Like most PHP code, it looks ugly :P
